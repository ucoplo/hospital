<?php

namespace deposito_central\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;
/**
 * This is the model class for table "perdidas".
 *
 * @property integer $DP_NROREM
 * @property string $DP_FECHA
 * @property string $DP_HORA
 * @property string $DP_MOTIVO
 * @property string $DP_CODOPE
 * @property string $DP_DEPOSITO
 *
 * @property Perdfar[] $perdfars
 * @property Deposito $pEDEPOSITO
 * @property Legajos $pECODOPE
 * @property Motivo_perdida $pEMOTIVO
 */
class Perdidas extends \yii\db\ActiveRecord
{
    public $renglones;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dc_perdidas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DP_FECHA', 'DP_HORA'], 'safe'],
            [['DP_MOTIVO'], 'string', 'max' => 4],
            [['DP_CODOPE'], 'string', 'max' => 6],
            [['DP_DEPOSITO'], 'string', 'max' => 2],
            [['renglones','DP_DEPOSITO','DP_MOTIVO'], 'required'],
            [['renglones'],'validateRenglonesCreate', 'on' => 'create'],
            [['DP_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DP_DEPOSITO' => 'DE_CODIGO']],
            [['DP_CODOPE'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['DP_CODOPE' => 'LE_NUMLEGA']],
            [['DP_MOTIVO'], 'exist', 'skipOnError' => true, 'targetClass' => Motivo_perdida::className(), 'targetAttribute' => ['DP_MOTIVO' => 'MP_COD']],
        ];
    }

      public function validateRenglonesCreate($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['DR_CODART'];
            $deposito = $this->DP_DEPOSITO;
            if (!$this->existe_articulo($codart, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['DR_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }

        $codigos_articulos = [];
        foreach($this->$attribute as $index => $renglon) {
            $codart_fecven = [$renglon['DR_CODART'],$renglon['DR_FECVTO']];
            
            if (in_array($codart_fecven, $codigos_articulos)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
                
            }
            else{
                $codigos_articulos[] = $codart_fecven;
            }
        }

        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['DR_CODART'];
            $deposito = $this->DP_DEPOSITO;
            $fecven = $renglon['DR_FECVTO'];
            $cantidad_existencia = $this->cantidad_vigente($codart,$deposito,$fecven);
            if ($cantidad_existencia<$renglon['DR_CANTID']) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Existencia insuficiente. Máximo $cantidad_existencia");
                
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DP_NROREM' => 'Número Pérdida',
            'DP_FECHA' => 'Fecha',
            'DP_HORA' => 'Hora',
            'DP_MOTIVO' => 'Motivo',
            'DP_CODOPE' => 'Personal de Depósito',
            'DP_DEPOSITO' => 'Depósito',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerdfars()
    {
        return $this->hasMany(Perdidas_renglones::className(), ['DR_NROREM' => 'DP_NROREM']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DP_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperador()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'DP_CODOPE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMotivo()
    {
        return $this->hasOne(Motivo_perdida::className(), ['MP_COD' => 'DP_MOTIVO']);
    }

    public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
        $depositos = Yii::$app->params['depositos_central'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public function getListaMotivos()
    {
        $opciones = Motivo_perdida::find();
        $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'MP_COD', 'MP_NOM');
    }

     public function cantidad_vigente($codart,$deposito,$fecven){

       $cantidad =  Vencimientos::find()
                   ->where(['DT_CODART' => $codart,'DT_DEPOSITO' => $deposito,'DT_FECVEN' => $fecven])
                   ->andWhere([">", 'DT_SALDO', 0])->sum('DT_SALDO');

       return $cantidad;
    }

     private function existe_articulo($codart,$deposito){

        if (($model = ArticGral::findOne(['AG_CODIGO' => $codart, 'AG_DEPOSITO' => $deposito])) !== null) {
            return true;
        } else {
            return false;
        }
    }

}

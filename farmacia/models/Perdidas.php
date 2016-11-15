<?php

namespace farmacia\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;
/**
 * This is the model class for table "perdidas".
 *
 * @property integer $PE_NROREM
 * @property string $PE_FECHA
 * @property string $PE_HORA
 * @property string $PE_MOTIVO
 * @property string $PE_CODOPE
 * @property string $PE_DEPOSITO
 *
 * @property Perdfar[] $perdfars
 * @property Deposito $pEDEPOSITO
 * @property Legajos $pECODOPE
 * @property MotPerd $pEMOTIVO
 */
class Perdidas extends \yii\db\ActiveRecord
{
    public $renglones;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'perdidas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PE_FECHA', 'PE_HORA'], 'safe'],
            [['PE_MOTIVO'], 'string', 'max' => 4],
            [['PE_CODOPE'], 'string', 'max' => 6],
            [['PE_DEPOSITO'], 'string', 'max' => 2],
            [['renglones','PE_DEPOSITO','PE_MOTIVO'], 'required'],
            [['renglones'],'validateRenglonesCreate', 'on' => 'create'],
            [['PE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PE_DEPOSITO' => 'DE_CODIGO']],
            [['PE_CODOPE'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['PE_CODOPE' => 'LE_NUMLEGA']],
            [['PE_MOTIVO'], 'exist', 'skipOnError' => true, 'targetClass' => Motivo_perdida::className(), 'targetAttribute' => ['PE_MOTIVO' => 'MP_COD']],
        ];
    }

      public function validateRenglonesCreate($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['PF_CODMON'];
            $deposito = $this->PE_DEPOSITO;
            if (!$this->existe_articulo($codmon, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['PF_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }

        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon_fecven = [$renglon['PF_CODMON'],$renglon['PF_FECVTO']];
            
            if (in_array($codmon_fecven, $codigos_monodrogas)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
                
            }
            else{
                $codigos_monodrogas[] = $codmon_fecven;
            }
        }

        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['PF_CODMON'];
            $deposito = $this->PE_DEPOSITO;
            $fecven = $renglon['PF_FECVTO'];
            $cantidad_existencia = $this->cantidad_vigente($codmon,$deposito,$fecven);
            if ($cantidad_existencia<$renglon['PF_CANTID']) {
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
            'PE_NROREM' => 'Número Pérdida',
            'PE_FECHA' => 'Fecha',
            'PE_HORA' => 'Hora',
            'PE_MOTIVO' => 'Motivo',
            'PE_CODOPE' => 'Personal de Farmacia',
            'PE_DEPOSITO' => 'Depósito',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerdfars()
    {
        return $this->hasMany(Perdfar::className(), ['PF_NROREM' => 'PE_NROREM']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'PE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperador()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'PE_CODOPE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMotivo()
    {
        return $this->hasOne(Motivo_perdida::className(), ['MP_COD' => 'PE_MOTIVO']);
    }

    public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
        $depositos = Yii::$app->params['depositos_farmacia'];
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

     public function cantidad_vigente($codmon,$deposito,$fecven){

       $cantidad =  Vencimientos::find()
                   ->where(['TV_CODART' => $codmon,'TV_DEPOSITO' => $deposito,'TV_FECVEN' => $fecven])
                   ->andWhere([">", 'TV_SALDO', 0])->sum('TV_SALDO');

       return $cantidad;
    }

     private function existe_articulo($codmon,$deposito){

        if (($model = ArticGral::findOne(['AG_CODIGO' => $codmon, 'AG_DEPOSITO' => $deposito])) !== null) {
            return true;
        } else {
            return false;
        }
    }

}

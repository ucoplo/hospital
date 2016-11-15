<?php

namespace farmacia\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;
/**
 * This is the model class for table "fa_remit".
 *
 * @property integer $RE_NUM
 * @property string $RE_FECHA
 * @property string $RE_HORA
 * @property string $RE_CODOPE
 * @property string $RE_CONCEP
 * @property string $RE_TIPMOV
 * @property string $RE_DEPOSITO
 * @property integer $RE_REMDEP
 *
 * @property Deposito $rEDEPOSITO
 * @property Legajos $rECODOPE
 * @property RsEncab $rEREMDEP
 * @property RemMov[] $remMovs
 */

class Remito_adquisicion extends \yii\db\ActiveRecord
{
    public $renglones;

    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fa_remit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RE_FECHA', 'RE_HORA'], 'safe'],
            [['RE_DEPOSITO','RE_FECHA', 'RE_HORA','RE_CONCEP','RE_TIPMOV','renglones'], 'required'],
            [['RE_CONCEP'], 'string'],
            [['RE_REMDEP'], 'integer'],
            [['RE_CODOPE'], 'string', 'max' => 6],
            [['RE_TIPMOV'], 'string', 'max' => 1],
            [['RE_DEPOSITO'], 'string', 'max' => 2],
            [['renglones'],'validateRenglones'],
           [['RE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['RE_DEPOSITO' => 'DE_CODIGO']],
            [['RE_CODOPE'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['RE_CODOPE' => 'LE_NUMLEGA']],
            [['RE_REMDEP'], 'exist', 'skipOnError' => true, 'targetClass' => Remito_deposito::className(), 'targetAttribute' => ['RE_REMDEP' => 'RS_NROREM']],
        ];
    }

    public function validateRenglones($attribute, $params)
    {

        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['RM_CODMON'];
            $deposito = $this->RE_DEPOSITO;
            if (!$this->existe_articulo($codmon, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['RM_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][RM_CANTID]';
                $this->addError($key, 'Cantidad debe ser un número');
            }
        }

        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['precio_compra'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El Precio debe ser un número');
            }
        }

        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['RM_CODMON'];
            if (in_array($codmon, $codigos_monodrogas)) {
                $key = $attribute . '[' . $index . '][RM_CODMON]';
                $this->addError($key, 'La Monodroga no puede repetirse');
            }
            else{
                $codigos_monodrogas[] = $codmon;
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RE_NUM' => 'Nº de Remito',
            'RE_FECHA' => 'Fecha',
            'RE_HORA' => 'Hora',
            'RE_CODOPE' => 'Personal de Farmacia',
            'RE_CONCEP' => 'Concepto',
            'RE_TIPMOV' => 'Tipo de Adquisición',
            'RE_DEPOSITO' => 'Depósito destino',
            'RE_REMDEP' => 'Número de remito de Depósito',
            'renglones' => 'Renglón',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperador()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'RE_CODOPE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemitoDeposito()
    {
        return $this->hasOne(Remito_deposito::className(), ['RS_NROREM' => 'RE_REMDEP']);
    }
   
    /**
     * @return \yii\db\ActiveQuery
     */
    public function  getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'RE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones()
    {
        return $this->hasMany(Remito_adquisicion_renglones::className(), ['RM_RENUM' => 'RE_NUM']);
    }

     public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
        $depositos = Yii::$app->params['depositos_farmacia'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public function tipo_movimiento(){
         if ($this->RE_TIPMOV =='C'){
            return "Compra";
        }
         elseif ($this->RE_TIPMOV =='D'){
            return "Donación";
        }
         else{
            return "Depósito";
        }
    }

    public function deposito_externo($campo){
        if ($campo=="value") {
            if ($this->RE_TIPMOV=='T'){
                return $this->RE_REMDEP;
            }
            elseif ($this->RE_TIPMOV =='C'){
                return "Compra";
            }
             else {
                return "Donación";
            }
        }
        else{
            if ($this->RE_TIPMOV=='T'){
                return 'Número de remito de Depósito';
            }
            else{
                return 'Tipo de Adquisición';
            }
        }
    }
     private function existe_articulo($codmon,$deposito){

        if (($model = ArticGral::findOne(['AG_CODIGO' => $codmon, 'AG_DEPOSITO' => $deposito])) !== null) {
            return true;
        } else {
            return false;
        }
    }

}

<?php

namespace deposito_central\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;
/**
 * This is the model class for table "fa_remit".
 *
 * @property integer $RA_NUM
 * @property string $RA_FECHA
 * @property string $RA_HORA
 * @property string $RA_CODOPE
 * @property string $RA_CONCEP
 * @property string $RA_TIPMOV
 * @property string $RA_DEPOSITO
 * @property integer $RA_OCNRO
 *
 * @property AdqReng[] $adqRengs
 * @property ArticGral[] $aRCODARTs
 * @property Deposito $rADEPOSITO
 * @property Legajos $rACODOPE
 * @property OrdenesCompra $rAOCNRO
 */

class Remito_adquisicion extends \yii\db\ActiveRecord
{
    public $renglones,$pedido;
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'remito_adq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RA_FECHA', 'RA_HORA'], 'safe'],
            [['RA_DEPOSITO','RA_FECHA', 'RA_HORA','RA_CONCEP','RA_TIPMOV','renglones'], 'required'],
            [['RA_CONCEP'], 'string'],
            [['RA_OCNRO'],'string', 'max' => 10],
            [['RA_CODOPE'], 'string', 'max' => 6],
            [['RA_TIPMOV'], 'string', 'max' => 1],
            [['RA_DEPOSITO'], 'string', 'max' => 2],
            [['renglones'],'validateRenglones'],
            [['renglones'],'validateRenglonesOrden', 'on' => 'create_orden'],
            [['RA_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['RA_DEPOSITO' => 'DE_CODIGO']],
            [['RA_CODOPE'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['RA_CODOPE' => 'LE_NUMLEGA']],
            [['RA_OCNRO'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenCompra::className(), 'targetAttribute' => ['RA_OCNRO' => 'OC_NRO']],
            
        ];
    }

    public function validateRenglonesOrden($attribute, $params)
    {

        $this->validateRenglones($attribute, $params);

        //verificar cantidad pendiente de recepción
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['AR_CODART'];
            $deposito = $this->RA_DEPOSITO;
            $cantidad_pendiente = $this->cantidad_pendiente($codart,$deposito);
            if ($cantidad_pendiente<$renglon['AR_CANTID']) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "La cantidad pendiente de recepción es menor. Máximo $cantidad_pendiente");
            }
        }
    }

    public function cantidad_pendiente($codart,$deposito){

       $pedido = $this->pedido;
       // $cantidad_pedida =  Pedido_adquisicion_renglones::find()
       //             ->where(['PE_CODART' => $codart,'PE_DEPOSITO' => $deposito,'PE_NUM'=>$pedido])
       //             ->sum('PE_CANT');
       //            //->groupBy(['DT_CODART', 'DT_DEPOSITO'])->one();
        $query =  OrdenCompra_renglones::find()
                   ->where(['EN_CODART' => $codart,'EN_DEPOSITO' => $deposito]);

        $query->joinWith(['orden_compra']);
                             
        $query->andFilterWhere(['OC_PEDADQ'=>$pedido]);

        $cantidad_comprada = $query->sum('EN_CANT');


        $query->join('INNER JOIN', 'remito_adq',
                 "remito_adq.RA_OCNRO = OC_NRO");  

        $query->join('INNER JOIN', 'adq_reng',
                 "adq_reng.AR_RENUM = RA_NUM"); 

        $cantidad_recibida = $query->sum('AR_CANTID'); 

        return $cantidad_comprada-$cantidad_recibida;
    }

    public function validateRenglones($attribute, $params)
    {

        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['AR_CODART'];
            $deposito = $this->RA_DEPOSITO;
            if (!$this->existe_articulo($codart, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El artículo no existe en el depósito seleccionado');
                
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['AR_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
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

        $codigos_articulos = [];
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['AR_CODART'];
            if (in_array($codart, $codigos_articulos)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El artículo no puede repetirse');
            }
            else{
                $codigos_articulos[] = $codart;
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RA_NUM' => 'Nº de Remito',
            'RA_FECHA' => 'Fecha',
            'RA_HORA' => 'Hora',
            'RA_CODOPE' => 'Personal de Depósito Central',
            'RA_CONCEP' => 'Concepto',
            'RA_TIPMOV' => 'Tipo de Adquisición',
            'RA_DEPOSITO' => 'Depósito destino',
            'RA_OCNRO' => 'Número de Orden de Compra',
            'renglones' => 'Renglón',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperador()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'RA_CODOPE']);
    }

       
    /**
     * @return \yii\db\ActiveQuery
     */
    public function  getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'RA_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones()
    {
        return $this->hasMany(Remito_adquisicion_renglones::className(), ['AR_RENUM' => 'RA_NUM']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticulos()
    {
        return $this->hasMany(ArticGral::className(), ['AG_CODIGO' => 'AR_CODART', 'AG_DEPOSITO' => 'AR_DEPOSITO'])->viaTable('adq_reng', ['AR_RENUM' => 'RA_NUM']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrden_compra()
    {
        return $this->hasOne(OrdenCompra::className(), ['OC_NRO' => 'RA_OCNRO']);
    }

     public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
        $depositos = Yii::$app->params['depositos_central'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public function tipo_movimiento(){
         if ($this->RA_TIPMOV =='C'){
            return "Compra";
        }
         elseif ($this->RA_TIPMOV =='D'){
            return "Donación";
        }
         else{
            return "Orden Compra";
        }
    }

    public function deposito_externo($campo){
        if ($campo=="value") {
            if ($this->RA_TIPMOV=='O'){
                return $this->orden_compra->numero.'/'.$this->orden_compra->ejercicio;
            }
            elseif ($this->RA_TIPMOV =='C'){
                return "Compra";
            }
             else {
                return "Donación";
            }
        }
        else{
            if ($this->RA_TIPMOV=='O'){
                return 'Número de Orden de Compra';
            }
            else{
                return 'Tipo de Adquisición';
            }
        }
    }
     private function existe_articulo($codart,$deposito){

        if (($model = ArticGral::findOne(['AG_CODIGO' => $codart, 'AG_DEPOSITO' => $deposito])) !== null) {
            return true;
        } else {
            return false;
        }
    }

}

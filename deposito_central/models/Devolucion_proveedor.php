<?php

namespace deposito_central\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;
/**
 * This is the model class for table "devoprov".
 *
 * @property integer $DD_NROREM
 * @property string $DD_FECHA
 * @property string $DD_HORA
 * @property string $DD_PROVE
 * @property string $DD_CODOPE
 * @property string $DD_DEPOSITO
 * @property string $DD_COMENTARIO
 *
 * @property Labo $proveedor
 * @property Legajos $operador
 * @property Deposito $deposito
 */
class Devolucion_proveedor extends \yii\db\ActiveRecord
{


    public $renglones;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dc_devoprov';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DD_FECHA', 'DD_HORA'], 'safe'],
            [['DD_DEPOSITO','DD_COMENTARIO','DD_PROVE','renglones'],'required'],
            [['DD_PROVE'], 'string', 'max' => 5],
            [['DD_CODOPE'], 'string', 'max' => 6],
            [['DD_DEPOSITO'], 'string', 'max' => 2],
            [['DD_COMENTARIO'], 'string'],
            [['DD_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DD_DEPOSITO' => 'DE_CODIGO']],
            [['DD_PROVE'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedores::className(), 'targetAttribute' => ['DD_PROVE' => 'PR_CODIGO']],
            [['DD_CODOPE'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['DD_CODOPE' => 'LE_NUMLEGA']],
            [['renglones'],'validateRenglonesCreate', 'on' => 'create'],
            [['renglones'],'validateRenglonesUpdate', 'on' => 'update'],
            
             
        ];
    }

     public function validateRenglonesCreate($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['DP_CODART'];
            $deposito = $this->DD_DEPOSITO;
            if (!$this->existe_articulo($codart, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }
        
        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['DP_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }

        $codigos_articulos = [];
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['DP_CODART'];
            if (in_array($codart, $codigos_articulos)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
                
            }
            else{
                $codigos_articulos[] = $codart;
            }
        }

        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['DP_CODART'];
            $deposito = $this->DD_DEPOSITO;
            $fecven = $renglon['DP_FECVTO'];
            $cantidad_existencia = $this->cantidad_vigente($codart,$deposito,$fecven);
            if ($cantidad_existencia<$renglon['DP_CANTID']) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Existencia insuficiente. Máximo $cantidad_existencia");
                
            }
        }
    }

    public function validateRenglonesUpdate($attribute, $params)
    {

        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['DP_CODART'];
            $deposito = $this->DD_DEPOSITO;
            if (!existe_articulo($codart, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['DP_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }
         
         $codigos_articulos = [];
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['DP_CODART'];
            if (in_array($codart, $codigos_articulos)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
                
            }
            else{
                $codigos_articulos[] = $codart;
            }
        }
         
        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['DP_CODART'];
            $deposito = $this->DD_DEPOSITO;
            $fecven = $renglon['DP_FECVTO'];
            $cantidad_existencia = $this->cantidad_vigente($codart,$deposito,$fecven);
                        
            $cant_ent_renglones = Devolucion_proveedor_renglones::find()->where(['DP_NROREM' => $this->DD_NROREM,'DP_CODART' => $codart])->sum('DP_CANTID');
            $cantidad_existencia += $cant_ent_renglones;

            $cant_ent = $this->cant_ent_medicamento($this->$attribute,$codart);
            if ($cantidad_existencia<$cant_ent) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Existencia insuficiente. Máximo $cantidad_existencia");
                
            }
            
        }
    }

    private function cant_ent_medicamento($renglones,$codart){

      $cantidad_retirada = 0;

      foreach($renglones as $index => $renglon) {

        if ($codart == $renglon['DP_CODART']){
          $cantidad_retirada += $renglon['DP_CANTID'];
        }

      }
     
      return $cantidad_retirada; 

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DD_NROREM' => 'Nº Remito',
            'DD_FECHA' => 'Fecha',
            'DD_HORA' => 'Hora',
            'DD_PROVE' => 'Proveedor',
            'DD_CODOPE' => 'Personal de Farmacia',
            'DD_DEPOSITO' => 'Depósito Origen',
            'DD_COMENTARIO' => 'Comentario',
            'renglones' => "Renglón"
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DD_DEPOSITO']);
    }

     public function getProveedor()
    {
         if (isset($this->DD_PROVE) && !empty($this->DD_PROVE)){
              return $this->hasOne(Proveedores::className(), ['PR_CODIGO' => 'DD_PROVE']);
          }
        
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperador()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'DD_CODOPE']);
    }


      public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
        $depositos = Yii::$app->params['depositos_central'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public function descripcion_proveedor(){
        $proveedor= Proveedores::findOne($this->DD_PROVE);
        return $proveedor->PR_RAZONSOC;
    }

     public function cantidad_vigente($codart,$deposito,$fecven){

       $cantidad =  Vencimientos::find()
                   ->where(['DT_CODART' => $codart,'DT_DEPOSITO' => $deposito,'DT_FECVEN' => $fecven])
                   ->andWhere([">", 'DT_SALDO', 0])->sum('DT_SALDO');
                  //->groupBy(['DT_CODART', 'DT_DEPOSITO'])->one();

       
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

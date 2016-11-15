<?php

namespace farmacia\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;
/**
 * This is the model class for table "devoprov".
 *
 * @property integer $DE_NROREM
 * @property string $DE_FECHA
 * @property string $DE_HORA
 * @property string $DE_PROVE
 * @property string $DE_CODOPE
 * @property string $DE_DEPOSITO
 * @property string $DE_DESTINO
 * @property string $DE_COMENTARIO
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
        return 'devoprov';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DE_FECHA', 'DE_HORA'], 'safe'],
            [['DE_DEPOSITO','DE_COMENTARIO','DE_DESTINO','renglones'],'required'],
            [['DE_PROVE'], 'string', 'max' => 4],
            [['DE_CODOPE'], 'string', 'max' => 6],
            [['DE_DEPOSITO'], 'string', 'max' => 2],
            [['DE_COMENTARIO'], 'string'],
            [['DE_DESTINO'], 'string', 'max' => 1],
            [['DE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DE_DEPOSITO' => 'DE_CODIGO']],
            [['DE_PROVE'], 'exist', 'skipOnError' => true, 'targetClass' => Labo::className(), 'targetAttribute' => ['DE_PROVE' => 'LA_CODIGO']],
            [['DE_CODOPE'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['DE_CODOPE' => 'LE_NUMLEGA']],
            [['renglones'],'validateRenglonesCreate', 'on' => 'create'],
            [['renglones'],'validateRenglonesUpdate', 'on' => 'update'],
            ['DE_PROVE', 'required', 'when' => function ($model) {
                    return $model->DE_DESTINO == 'E';
                }, 'whenClient' => "function (attribute, value) {
                    return $('#devolucion_proveedor-de_destino input:checked').val() == 'E';
                }"],    
             
        ];
    }

     public function validateRenglonesCreate($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DP_CODMON'];
            $deposito = $this->DE_DEPOSITO;
            if (!$this->existe_articulo($codmon, $deposito)) {
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

        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DP_CODMON'];
            if (in_array($codmon, $codigos_monodrogas)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
                
            }
            else{
                $codigos_monodrogas[] = $codmon;
            }
        }

        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DP_CODMON'];
            $deposito = $this->DE_DEPOSITO;
            $fecven = $renglon['DP_FECVTO'];
            $cantidad_existencia = $this->cantidad_vigente($codmon,$deposito,$fecven);
            if ($cantidad_existencia<$renglon['DP_CANTID']) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Existencia insuficiente. Máximo $cantidad_existencia");
                
            }
        }
    }

    public function validateRenglonesUpdate($attribute, $params)
    {

        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DP_CODMON'];
            $deposito = $this->DE_DEPOSITO;
            if (!existe_articulo($codmon, $deposito)) {
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
         
         $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DP_CODMON'];
            if (in_array($codmon, $codigos_monodrogas)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no puede repetirse');
                
            }
            else{
                $codigos_monodrogas[] = $codmon;
            }
        }
         
        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DP_CODMON'];
            $deposito = $this->DE_DEPOSITO;
            $fecven = $renglon['DP_FECVTO'];
            $cantidad_existencia = $this->cantidad_vigente($codmon,$deposito,$fecven);
                        
            $cant_ent_renglones = Devolucion_proveedor_renglones::find()->where(['DP_NROREM' => $this->DE_NROREM,'DP_CODMON' => $codmon])->sum('DP_CANTID');
            $cantidad_existencia += $cant_ent_renglones;

            $cant_ent = $this->cant_ent_medicamento($this->$attribute,$codmon);
            if ($cantidad_existencia<$cant_ent) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Existencia insuficiente. Máximo $cantidad_existencia");
                
            }
            
        }
    }

    private function cant_ent_medicamento($renglones,$codmon){

      $cantidad_retirada = 0;

      foreach($renglones as $index => $renglon) {

        if ($codmon == $renglon['DP_CODMON']){
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
            'DE_NROREM' => 'Nº Remito',
            'DE_FECHA' => 'Fecha',
            'DE_HORA' => 'Hora',
            'DE_PROVE' => 'Proveedor',
            'DE_CODOPE' => 'Personal de Farmacia',
            'DE_DEPOSITO' => 'Depósito Origen',
            'DE_DESTINO' => 'Destino',
            'DE_COMENTARIO' => 'Comentario',
            'renglones' => "Renglón"
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DE_DEPOSITO']);
    }

     public function getProveedor()
    {
         if (isset($this->DE_PROVE) && !empty($this->DE_PROVE)){
              return $this->hasOne(Labo::className(), ['LA_CODIGO' => 'DE_PROVE']);
          }
        else{
            $proveedor = new Labo();
            $proveedor->LA_CODIGO = 'DEP';
            $proveedor->LA_NOMBRE = "Depósito Central";
            return $proveedor;
        } 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperador()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'DE_CODOPE']);
    }


      public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
        $depositos = Yii::$app->params['depositos_farmacia'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public function descripcion_proveedor(){

         if (isset($this->DE_PROVE) && !empty($this->DE_PROVE)){
            $labo= Labo::findOne($this->DE_PROVE);
            return $labo->LA_NOMBRE;
        }
        else{
            return "Depósito Central";
        }
    }

     public function cantidad_vigente($codmon,$deposito,$fecven){

       $cantidad =  Vencimientos::find()
                   ->where(['TV_CODART' => $codmon,'TV_DEPOSITO' => $deposito,'TV_FECVEN' => $fecven])
                   ->andWhere([">", 'TV_SALDO', 0])->sum('TV_SALDO');
                  //->groupBy(['TV_CODART', 'TV_DEPOSITO'])->one();

       
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

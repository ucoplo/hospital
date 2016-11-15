<?php

namespace deposito_central\models;

use Yii;
use yii\validators\NumberValidator;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "consme3".
 *
 * @property integer $PE_NROREM
 * @property string $PE_FECHA
 * @property string $PE_HORA
 * @property string $PE_SERSOL
 * @property string $PE_ENFERM
 * @property string $PE_CODOPE
 * @property string $PE_DEPOSITO
 * @property integer $PE_PROCESADO
 * @property integer $PE_NUMVALE
 *
 * @property Deposito $deposito
 * @property Legajos $enfermero
 * @property Legajos $operador
 * @property Servicio $cMSERSOL
 * @property Planfar[] $planfars
 */
class Planilla_entrega extends \yii\db\ActiveRecord
{
     public $renglones;
     public $pedido_insumos;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'plan_ent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['renglones','PE_DEPOSITO','PE_ENFERM','PE_SERSOL'], 'required'],
            [['renglones'],'validateRenglonesCreate', 'on' => 'create'],
            [['renglones'],'validateRenglonesUpdate', 'on' => 'update'],
            [['PE_FECHA', 'PE_HORA','pedido_insumos'], 'safe'],
            [['PE_SERSOL'], 'string', 'max' => 3],
            [['PE_PROCESADO', 'PE_NUMVALE'], 'integer'],
            [['PE_ENFERM', 'PE_CODOPE'], 'string', 'max' => 6],
            [['PE_DEPOSITO'], 'string', 'max' => 2],
            [['PE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PE_DEPOSITO' => 'DE_CODIGO']],
            [['PE_ENFERM'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['PE_ENFERM' => 'LE_NUMLEGA']],
            [['PE_CODOPE'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['PE_CODOPE' => 'LE_NUMLEGA']],
            [['PE_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['PE_SERSOL' => 'SE_CODIGO']],
            [['PE_NUMVALE'], 'exist', 'skipOnError' => true, 'targetClass' => PedidoInsumos::className(), 'targetAttribute' => ['PE_NUMVALE' => 'VD_NUMVALE']],
        ];
    }

       public function validateRenglonesCreate($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['PR_CODART'];
            $deposito = $this->PE_DEPOSITO;
            if (!$this->existe_articulo($codart, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['PR_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }

        $codigos_articulos = [];
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['PR_CODART'];
            if (in_array($codart, $codigos_articulos)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El artículo no puede repetirse');
                
            }
            else{
                $codigos_articulos[] = $codart;
            }
        }

        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['PR_CODART'];
            $deposito = $this->PE_DEPOSITO;
            $cantidad_existencia = $this->cantidad_vigente($codart,$deposito);
            if ($cantidad_existencia<$renglon['PR_CANTID']) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Existencia insuficiente. Máximo $cantidad_existencia");
                
            }
        }
    }

    public function validateRenglonesUpdate($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['PR_CODART'];
            $deposito = $this->PE_DEPOSITO;
            if (!$this->existe_articulo($codart, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El artículo no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['PR_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }
         
         $codigos_articulos = [];
        foreach($this->$attribute as $index => $renglon) {
            $codart = $renglon['PR_CODART'];
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
            $codart = $renglon['PR_CODART'];
            $deposito = $this->PE_DEPOSITO;
            $cantidad_existencia = $this->cantidad_vigente($codart,$deposito);
            
            $cant_ent_renglones = Planilla_entrega_renglones::find()->where(['PR_NROREM' => $this->PE_NROREM,'PR_CODART' => $codart])->sum('PR_CANTID');
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

        if ($codart == $renglon['PR_CODART']){
          $cantidad_retirada += $renglon['PR_CANTID'];
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
            'PE_NROREM' => 'Número de remito',
            'PE_FECHA' => 'Fecha',
            'PE_HORA' => 'Hora',
            'PE_SERSOL' => 'Servicio Solicitante',
            'PE_ENFERM' => 'Personal de Enfermería',
            'PE_CODOPE' => 'Personal de deposito_central',
            'PE_DEPOSITO' => 'Depósito',
            'PE_PROCESADO' => 'Entregó',
            'PE_NUMVALE' => 'Número Vale de Pedido',
        ];
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
    public function getEnfermero()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'PE_ENFERM']);
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
    public function getServicio()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'PE_SERSOL']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones_planilla()
    {
        return $this->hasMany(Planilla_entrega_renglones::className(), ['PR_NROREM' => 'PE_NROREM']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevoluciones()
    {
        return $this->hasMany(Devolucion_salas::className(), ['DE_NUMREMOR' => 'PE_NROREM']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedidoInsumo()
    {
        return $this->hasOne(PedidoInsumos::className(), ['VD_NUMVALE' => 'PE_NUMVALE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemitos_Deposito()
    {
        return $this->hasMany(Remito_deposito::className(), ['RS_NUMPED' => 'PE_NROREM']);
    }

     public function cantidad_vigente($codart,$deposito){

       $cantidad =  Vencimientos::find()
                   ->where(['DT_CODART' => $codart,'DT_DEPOSITO' => $deposito,])
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

     public static function getListaServicios()
    {
        $opciones = Servicio::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'SE_CODIGO', 'SE_DESCRI');
    }

     public function getListaDeposito()
    {
        $opciones = Deposito::find();
        $depositos = Yii::$app->params['depositos_central'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

     public static function getListaEnfermeros()
    {
        $opciones = Legajos::find()->andFilterWhere([
            'LE_ACTIVO' => 'T'])->asArray()->all();
        return ArrayHelper::map($opciones, 'LE_NUMLEGA', 'LE_APENOM');
    }
}

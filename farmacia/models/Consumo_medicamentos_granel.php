<?php

namespace farmacia\models;

use Yii;
use yii\validators\NumberValidator;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "consme3".
 *
 * @property integer $CM_NROREM
 * @property string $CM_FECHA
 * @property string $CM_HORA
 * @property string $CM_SERSOL
 * @property string $CM_ENFERM
 * @property string $CM_CODOPE
 * @property string $CM_DEPOSITO
 * @property integer $CM_PROCESADO
 *
 * @property Deposito $deposito
 * @property Legajos $enfermero
 * @property Legajos $operador
 * @property Servicio $cMSERSOL
 * @property Planfar[] $planfars
 */
class Consumo_medicamentos_granel extends \yii\db\ActiveRecord
{
     public $renglones;
     public $vale_granel;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'consme3';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['renglones'], 'required'],
            [['renglones'],'validateRenglonesCreate', 'on' => 'create'],
            [['renglones'],'validateRenglonesUpdate', 'on' => 'update'],
            [['CM_FECHA', 'CM_HORA','vale_granel'], 'safe'],
            [['CM_SERSOL'], 'string', 'max' => 3],
            [['CM_PROCESADO'], 'integer'],
            [['CM_ENFERM', 'CM_CODOPE'], 'string', 'max' => 6],
            [['CM_DEPOSITO'], 'string', 'max' => 2],
            [['CM_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['CM_DEPOSITO' => 'DE_CODIGO']],
            [['CM_ENFERM'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['CM_ENFERM' => 'LE_NUMLEGA']],
            [['CM_CODOPE'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['CM_CODOPE' => 'LE_NUMLEGA']],
            [['CM_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['CM_SERSOL' => 'SE_CODIGO']],
        ];
    }

       public function validateRenglonesCreate($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['PF_CODMON'];
            $deposito = $this->CM_DEPOSITO;
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
            $codmon = $renglon['PF_CODMON'];
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
            $codmon = $renglon['PF_CODMON'];
            $deposito = $this->CM_DEPOSITO;
            $cantidad_existencia = $this->cantidad_vigente($codmon,$deposito);
            if ($cantidad_existencia<$renglon['PF_CANTID']) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Existencia insuficiente. Máximo $cantidad_existencia");
                
            }
        }
    }

    public function validateRenglonesUpdate($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['PF_CODMON'];
            $deposito = $this->CM_DEPOSITO;
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
            $codmon = $renglon['PF_CODMON'];
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
            $codmon = $renglon['PF_CODMON'];
            $deposito = $this->CM_DEPOSITO;
            $cantidad_existencia = $this->cantidad_vigente($codmon,$deposito);
            
            $cant_ent_renglones = Consumo_medicamentos_granel_renglones::find()->where(['PF_NROREM' => $this->CM_NROREM,'PF_CODMON' => $codmon])->sum('PF_CANTID');
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

        if ($codmon == $renglon['PF_CODMON']){
          $cantidad_retirada += $renglon['PF_CANTID'];
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
            'CM_NROREM' => 'Número de remito',
            'CM_FECHA' => 'Fecha',
            'CM_HORA' => 'Hora',
            'CM_SERSOL' => 'Servicio Solicitante',
            'CM_ENFERM' => 'Personal de Enfermería',
            'CM_CODOPE' => 'Personal de Farmacia',
            'CM_DEPOSITO' => 'Depósito',
            'CM_PROCESADO' => 'Entregó',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'CM_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnfermero()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'CM_ENFERM']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperador()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'CM_CODOPE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicio()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'CM_SERSOL']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones_planilla()
    {
        return $this->hasMany(Consumo_medicamentos_granel_renglones::className(), ['PF_NROREM' => 'CM_NROREM']);
    }

     public function cantidad_vigente($codmon,$deposito){

       $cantidad =  Vencimientos::find()
                   ->where(['TV_CODART' => $codmon,'TV_DEPOSITO' => $deposito,])
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

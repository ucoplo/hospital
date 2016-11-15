<?php

namespace farmacia\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;

/**
 * This is the model class for table "ambu_enc".
 *
 * @property integer $AM_HISCLI
 * @property integer $AM_NUMVALE
 * @property string $AM_FECHA
 * @property string $AM_HORA
 * @property string $AM_PROG
 * @property string $AM_ENTIDER
 * @property string $AM_MEDICO
 * @property string $AM_DEPOSITO
 * @property string $AM_FARMACEUTICO
 *
 * @property Deposito $aMDEPOSITO
 * @property EntiDer $aMENTIDER
 * @property Legajos $aMMEDICO
 * @property Legajos $aMFARMACEUTICO
 * @property Paciente $aMHISCLI
 * @property Programa $aMPROG
 * @property AmbuRen[] $ambuRens
 */
class Ambulatorios_ventanilla extends \yii\db\ActiveRecord
{

    public $renglones;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ambu_enc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AM_HISCLI', 'AM_NUMVALE'], 'integer'],
            [['AM_HISCLI','AM_FECHA', 'AM_HORA','AM_ENTIDER','AM_MEDICO','AM_DEPOSITO','AM_PROG','renglones'], 'required'],
            [['AM_FECHA', 'AM_HORA'], 'safe'],
            [['AM_PROG', 'AM_DEPOSITO'], 'string', 'max' => 2],
            [['AM_ENTIDER'], 'string', 'max' => 3],
            [['AM_MEDICO', 'AM_FARMACEUTICO'], 'string', 'max' => 6],
            [['renglones'],'validateRenglonesCreate', 'on' => 'create'],
            [['renglones'],'validateRenglonesUpdate', 'on' => 'update'],
            [['AM_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['AM_DEPOSITO' => 'DE_CODIGO']],
            [['AM_PROG'], 'exist', 'skipOnError' => true, 'targetClass' => Programa::className(), 'targetAttribute' => ['AM_PROG' => 'PR_CODIGO']],
            [['AM_HISCLI'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::className(), 'targetAttribute' => ['AM_HISCLI' => 'PA_HISCLI']],
            [['AM_MEDICO'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['AM_MEDICO' => 'LE_NUMLEGA']],
            [['AM_ENTIDER'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadDerivadora::className(), 'targetAttribute' => ['AM_ENTIDER' => 'ED_COD']],
            [['AM_FARMACEUTICO'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['AM_FARMACEUTICO' => 'LE_NUMLEGA']],
        ];
    }

      public function validateRenglonesCreate($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['AM_CODMON'];
            $deposito = $this->AM_DEPOSITO;
            if (!$this->existe_articulo($codmon, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }
        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['AM_CANTPED'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][AM_CANTPED]';
                $this->addError($key, 'Cantidad debe ser un número');
            }


              $obj_renglon = new Ambulatorios_ventanilla_renglones();

              $obj_renglon->AM_NUMVALE = 1;
              $obj_renglon->AM_DEPOSITO = $this->AM_DEPOSITO;
              $obj_renglon->AM_NUMREN = 1;
              $obj_renglon->AM_CODMON = $renglon['AM_CODMON'];
            
              $obj_renglon->AM_CANTPED = $renglon['AM_CANTPED'];
              $obj_renglon->AM_CANTENT = $renglon['AM_CANTENT'];
              $obj_renglon->AM_FECVTO = $renglon['AM_FECVTO'];

              if (!$obj_renglon->validate() ){ 

                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'La cantidad Retirada debe ser menor o igual que la pedida');
              }
        }

        //Valida medicamentos repetidos en renglones
        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['AM_CODMON'];
            if (in_array($codmon, $codigos_monodrogas)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'La Monodroga no puede repetirse');
                
            }
            else{
                $codigos_monodrogas[] = $codmon;
            }
        }

        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['AM_CODMON'];
            $deposito = $this->AM_DEPOSITO;
            $cantidad_existencia = $this->cantidad_vigente($codmon,$deposito);
            if ($cantidad_existencia<$renglon['AM_CANTENT']) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Existencia insuficiente. Máximo $cantidad_existencia");
                
            }
        }
    }

    public function validateRenglonesUpdate($attribute, $params)
    {
        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['AM_CANTPED'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][AM_CANTPED]';
                $this->addError($key, 'Cantidad debe ser un número');
            }


               $obj_renglon = new Ambulatorios_ventanilla_renglones();

              $obj_renglon->AM_NUMVALE = 1;
              $obj_renglon->AM_DEPOSITO = $this->AM_DEPOSITO;
              $obj_renglon->AM_NUMREN = 1;
              $obj_renglon->AM_CODMON = $renglon['AM_CODMON'];
               
              $obj_renglon->AM_CANTPED = $renglon['AM_CANTPED'];
              $obj_renglon->AM_CANTENT = $renglon['AM_CANTENT'];
              $obj_renglon->AM_FECVTO = $renglon['AM_FECVTO'];

              if (!$obj_renglon->validate() ){ 

                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'La cantidad Retirada debe ser menor o igual que la pedida');
              }

        }

        //verificar existencia
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['AM_CODMON'];
            $deposito = $this->AM_DEPOSITO;
            $cantidad_existencia = $this->cantidad_vigente($codmon,$deposito);
            
            $cant_ent_renglones = Ambulatorios_ventanilla_renglones::find()->where(['AM_NUMVALE' => $this->AM_NUMVALE,'AM_CODMON' => $codmon])->sum('AM_CANTENT');
            $cantidad_existencia += $cant_ent_renglones;

            $cant_ent = $this->cant_ent_medicamento($this->$attribute,$codmon);
            if ($cantidad_existencia<$cant_ent) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Existencia insuficiente. Máximo $cantidad_existencia - Ingresado $cant_ent");
                
            }
            
        }
    }

    private function cant_ent_medicamento($renglones,$codmon){

      $cantidad_retirada = 0;

      foreach($renglones as $index => $renglon) {

        if ($codmon == $renglon['AM_CODMON']){
          $cantidad_retirada += $renglon['AM_CANTENT'];
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
            'AM_HISCLI' => ' Historia clínica',
            'AM_NUMVALE' => 'Número',
            'AM_FECHA' => 'Fecha',
            'AM_HORA' => 'Hora',
            'AM_PROG' => 'Programa',
            'AM_ENTIDER' => 'Entidad derivadora',
            'AM_MEDICO' => 'Médico',
            'AM_DEPOSITO' => 'Depósito',
            'AM_FARMACEUTICO' => 'Farmaceutico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'AM_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrograma()
    {
        return $this->hasOne(Programa::className(), ['PR_CODIGO' => 'AM_PROG']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedico()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'AM_MEDICO']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaciente()
    {
        return $this->hasOne(Paciente::className(), ['PA_HISCLI' => 'AM_HISCLI']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidad()
    {
        return $this->hasOne(EntidadDerivadora::className(), ['ED_COD' => 'AM_ENTIDER']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones()
    {
        return $this->hasMany(Ambulatorios_ventanilla_renglones::className(), ['AM_NUMVALE' => 'AM_NUMVALE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFarmaceutico()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'AM_FARMACEUTICO']);
    }


     public static function getListaProgramas()
    {
        $opciones = Programa::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'PR_CODIGO', 'PR_NOMBRE');
    }

    public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
        $depositos = Yii::$app->params['depositos_farmacia'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

    public static function getListaEntidades()
    {
        $opciones = EntidadDerivadora::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'ED_COD', 'ED_DETALLE');
    }

    public static function getListaMedicos()
    {
        $opciones = Legajos::find()->andFilterWhere([
            'LE_ACTIVO' => 'T'])->andWhere(['<>', 'LE_MATRIC', ''])->asArray()->all();
        return ArrayHelper::map($opciones, 'LE_NUMLEGA', 'LE_APENOM');
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

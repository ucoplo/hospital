<?php

namespace farmacia\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "consmed".
 *
 * @property integer $CM_NROVAL
 * @property string $CM_NROREM
 * @property integer $CM_HISCLI
 * @property string $CM_FECHA
 * @property string $CM_HORA
 * @property string $CM_SERSOL
 * @property string $CM_CODOPE
 * @property string $CM_UNIDIAG
 * @property string $CM_CONDPAC
 * @property string $CM_SUPERV
 * @property string $CM_MEDICO
 * @property integer $CM_PROCESADO
 * @property string $CM_DEPOSITO
 * @property string $CM_IDINTERNA
 *
 * @property Deposito $deposito
 * @property Interna $cMIDINTERNA
 * @property Legajos $medico
 * @property Legajos $operador
 * @property Legajos $supervisor
 * @property Paciente $cMHISCLI
 * @property Servicio $cMSERSOL
 * @property Servicio $cMUNIDIAG
 * @property Valefar[] $valefars
 */
class Consumo_medicamentos_pacientes extends \yii\db\ActiveRecord
{
    public $vale_enfermeria;
    public $sala,$habitacion,$cama,$ingreso,$etiquetas;
    public $renglones;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'consmed';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CM_NROREM','renglones'], 'required'],
            [['CM_HISCLI', 'CM_PROCESADO'], 'integer'],
            [['CM_FECHA', 'CM_HORA','CM_IDINTERNA'], 'safe'],
            [['CM_CONDPAC'], 'string'],
            [['CM_NROREM'], 'string', 'max' => 12],
            [['CM_SERSOL', 'CM_UNIDIAG'], 'string', 'max' => 3],
            [['CM_CODOPE', 'CM_SUPERV', 'CM_MEDICO'], 'string', 'max' => 6],
            [['CM_DEPOSITO'], 'string', 'max' => 2],
            [['renglones'],'validateRenglonesCreate', 'on' => 'create'],
            [['renglones'],'validateRenglonesUpdate', 'on' => 'update'],
            [['CM_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['CM_DEPOSITO' => 'DE_CODIGO']],
            [['CM_IDINTERNA'], 'exist', 'skipOnError' => true, 'targetClass' => Interna::className(), 'targetAttribute' => ['CM_IDINTERNA' => 'IN_ID']],
            [['CM_MEDICO'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['CM_MEDICO' => 'LE_NUMLEGA']],
            [['CM_CODOPE'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['CM_CODOPE' => 'LE_NUMLEGA']],
            [['CM_SUPERV'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['CM_SUPERV' => 'LE_NUMLEGA']],
            [['CM_HISCLI'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::className(), 'targetAttribute' => ['CM_HISCLI' => 'PA_HISCLI']],
            [['CM_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['CM_SERSOL' => 'SE_CODIGO']],
            [['CM_UNIDIAG'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['CM_UNIDIAG' => 'SE_CODIGO']],
        ];
    }

      public function validateRenglonesCreate($attribute, $params)
    {
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['VA_CODMON'];
            $deposito = $this->CM_DEPOSITO;
            if (!$this->existe_articulo($codmon, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['VA_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }
        }

        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['VA_CODMON'];
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
            $codmon = $renglon['VA_CODMON'];
            $deposito = $this->CM_DEPOSITO;
            $cantidad_existencia = $this->cantidad_vigente($codmon,$deposito);
            if ($cantidad_existencia<$renglon['VA_CANTID']) {
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
            $numberValidator->validate($renglon['VA_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }
         
         $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['VA_CODMON'];
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
            $codmon = $renglon['VA_CODMON'];
            $deposito = $this->CM_DEPOSITO;
            $cantidad_existencia = $this->cantidad_vigente($codmon,$deposito);
            
            $cant_ent_renglones = Consumo_medicamentos_pacientes_renglones::find()->where(['VA_NROVALE' => $this->CM_NROVAL,'VA_CODMON' => $codmon])->sum('VA_CANTID');
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

        if ($codmon == $renglon['VA_CODMON']){
          $cantidad_retirada += $renglon['VA_CANTID'];
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
            'CM_HISCLI' => 'Paciente Historia Clínica',
            'CM_FECHA' => 'Fecha',
            'CM_HORA' => 'Hora',
            'CM_SERSOL' => 'Servicio Solicitante',
            'CM_CODOPE' => 'Personal de Farmacia',
            'CM_UNIDIAG' => 'Unidad de Diagnóstico',
            'CM_CONDPAC' => 'Condición del paciente',
            'CM_NROVAL' => 'Número Vale',
            'CM_SUPERV' => 'Personal de Enfermería',
            'CM_MEDICO' => 'Médico Solicitante',
            'CM_PROCESADO' => 'Entregó',
            'CM_DEPOSITO' => 'Depósito',
            'sala' => 'Sala Actual',
            'habitacion' => 'Nº Habitación',
            'cama' => 'Cama',
            'ingreso' => 'Ingresó',
            'CM_IDINTERNA' => 'Internación',
        ];
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'CM_DEPOSITO']);
    }

    public function getInternacion()
    {
        return $this->hasOne(Interna::className(), ['IN_ID' => 'CM_IDINTERNA']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedico()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'CM_MEDICO']);
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
    public function getSupervisor()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'CM_SUPERV']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaciente()
    {
        return $this->hasOne(Paciente::className(), ['PA_HISCLI' => 'CM_HISCLI']);
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
    public function getCMUNIDIAG()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'CM_UNIDIAG']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValefars()
    {
        return $this->hasMany(Consumo_medicamentos_pacientes_renglones::className(), ['VA_NROVALE' => 'CM_NROVAL']);
    }

     public static function getListaDeposito()
    {
        $opciones = Deposito::find();//->asArray()->all();
        $depositos = Yii::$app->params['depositos_farmacia'];
        $opciones->andFilterWhere(['in', 'DE_CODIGO', $depositos] );

       $opciones = $opciones->asArray()->all();

        
        return ArrayHelper::map($opciones, 'DE_CODIGO', 'DE_DESCR');
    }

     public function cantidad_vigente($codmon,$deposito){

       $cantidad =  Vencimientos::find()
                   ->where(['TV_CODART' => $codmon,'TV_DEPOSITO' => $deposito,])
                   ->andWhere([">", 'TV_SALDO', 0])->sum('TV_SALDO');
                  //->groupBy(['TV_CODART', 'TV_DEPOSITO'])->one();

       
       return $cantidad;
       
       

    }

    public function getCondicion_paciente(){
        if ($this->CM_CONDPAC=='I'){
            return "Internado";
        }elseif ($this->CM_CONDPAC=='A') {
            return "Ambulatorio";
        }else{
            return "Indefinido";
        }
    }

     public function get_renglones_remito($id=0,$condpac)
    {
        $query = Consumo_medicamentos_pacientes_renglones::find();

        // add conditions that should always apply here
        $query->joinWith(['vale']);
        
        $query->andFilterWhere([
            'CM_NROREM' => $id,
            'CM_CONDPAC' => $condpac,
        ]);

        $query->select(['VA_CODMON, VA_FECVTO, SUM(VA_CANTID) AS VA_CANTID'])
            ->groupBy(['VA_CODMON', 'VA_FECVTO']);
            

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
    private function existe_articulo($codmon,$deposito){

        if (($model = ArticGral::findOne(['AG_CODIGO' => $codmon, 'AG_DEPOSITO' => $deposito])) !== null) {
            return true;
        } else {
            return false;
        }
    }
}

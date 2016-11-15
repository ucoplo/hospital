<?php

namespace farmacia\models;

use Yii;
use yii\validators\NumberValidator;

/**
 * This is the model class for table "devoluc2".
 *
 * @property integer $DE_NRODEVOL
 * @property integer $DE_HISCLI
 * @property string $DE_FECHA
 * @property string $DE_HORA
 * @property string $DE_SERSOL
 * @property string $DE_CODOPE
 * @property string $DE_ENFERM
 * @property string $DE_UNIDIAG
 * @property integer $DE_NUMVALOR
 * @property string $DE_DEPOSITO
 * @property string $DE_IDINTERNA
 *
 * @property DevVal[] $devVals
 * @property Consmed $dENUMVALOR
 * @property Deposito $deposito
 * @property Interna $internacion
 * @property Legajos $operador
 * @property Legajos $enfermero
 * @property Paciente $dEHISCLI
 * @property Servicio $dESERSOL
 */
class Devolucion_salas_paciente extends \yii\db\ActiveRecord
{
    public $renglones;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'devoluc2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DE_HISCLI', 'DE_NUMVALOR'], 'integer'],
            [['DE_FECHA', 'DE_HORA'], 'safe'],
            [['DE_SERSOL', 'DE_UNIDIAG'], 'string', 'max' => 3],
            [['DE_CODOPE', 'DE_ENFERM'], 'string', 'max' => 6],
            [['DE_DEPOSITO'], 'string', 'max' => 2],
            [['renglones'], 'required'],
            [['renglones'],'validateRenglonesCreate', 'on' => 'create'],
            [['DE_NUMVALOR'], 'exist', 'skipOnError' => true, 'targetClass' => Consumo_medicamentos_pacientes::className(), 'targetAttribute' => ['DE_NUMVALOR' => 'CM_NROVAL']],
            [['DE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DE_DEPOSITO' => 'DE_CODIGO']],
             [['DE_IDINTERNA'], 'exist', 'skipOnError' => true, 'targetClass' => Interna::className(), 'targetAttribute' => ['DE_IDINTERNA' => 'IN_ID']],
            [['DE_CODOPE'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['DE_CODOPE' => 'LE_NUMLEGA']],
            [['DE_ENFERM'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['DE_ENFERM' => 'LE_NUMLEGA']],
            [['DE_HISCLI'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::className(), 'targetAttribute' => ['DE_HISCLI' => 'PA_HISCLI']],
            [['DE_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['DE_SERSOL' => 'SE_CODIGO']],
        ];
    }

    public function validateRenglonesCreate($attribute, $params)
    {

        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DV_CODMON'];
            $deposito = $this->DE_DEPOSITO;
            if (!$this->existe_articulo($codmon, $deposito)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'El medicamento no existe en el depósito seleccionado');
            }
        }

        $numberValidator = new NumberValidator();
        
        foreach($this->$attribute as $index => $renglon) {
            $error = null;
            $numberValidator->validate($renglon['DV_CANTID'], $error);

            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, 'Cantidad debe ser un número');
            }

           
        }

        $codigos_monodrogas = [];
        foreach($this->$attribute as $index => $renglon) {
            $codmon = $renglon['DV_CODMON'];
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
            $codmon = $renglon['DV_CODMON'];
            $deposito = $this->DE_DEPOSITO;
            $fecven = date('Y-m-d', strtotime(str_replace("/","-",$renglon['DV_FECVTO'])));
            $cantidad_entregada = $this->cantidad_entregada($codmon,$deposito,$fecven);

            if ($cantidad_entregada<$renglon['DV_CANTID']) {
                $key = $attribute . '[' . $index . '][descripcion]';
                $this->addError($key, "Máximo a Devolver s/Vale. $cantidad_entregada");
                
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DE_NRODEVOL' => 'Número Devolución',
            'DE_HISCLI' => 'Historia Clínica',
            'DE_FECHA' => 'Fecha',
            'DE_HORA' => 'Hora',
            'DE_SERSOL' => 'Servicio solicitante',
            'DE_CODOPE' => 'Personal de Farmacia',
            'DE_ENFERM' => 'Personal de Enfermería',
            'DE_UNIDIAG' => 'Unidad de diagnóstico solicitante',
            'DE_NUMVALOR' => 'Número del vale original',
            'DE_DEPOSITO' => 'Depósito',
            'DE_IDINTERNA' => 'Internación',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones()
    {
        return $this->hasMany(Devolucion_salas_paciente_renglones::className(), ['DV_NRODEVOL' => 'DE_NRODEVOL']);
    }

     public function getMonodrogas()
    {
        return $this
            ->hasOne(ArticGral::className(), ['AG_CODIGO' => 'DV_CODMON'])
            ->via('renglones');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVale_original()
    {
        return $this->hasOne(Consumo_medicamentos_pacientes::className(), ['CM_NROVAL' => 'DE_NUMVALOR']);
    }

    public function getMedico()
    {
        return $this->hasOne(Consumo_medicamentos_pacientes::className(), ['CM_NROVAL' => 'DE_NUMVALOR']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInternacion()
    {
        return $this->hasOne(Interna::className(), ['IN_ID' => 'DE_IDINTERNA']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperador()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'DE_CODOPE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnfermero()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'DE_ENFERM']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaciente()
    {
        return $this->hasOne(Paciente::className(), ['PA_HISCLI' => 'DE_HISCLI']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicio()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'DE_SERSOL']);
    }

    public function getMonodrogasVale()
    {
       $monodrogas = ArticGral::find();
       $monodrogas->join('INNER JOIN', 'valefar','valefar.VA_CODMON=artic_gral.AG_CODIGO AND valefar.VA_NROVALE ='.$this->DE_NUMVALOR);     

       return $monodrogas->all();
    }

    public function cantidad_entregada($codmon,$deposito,$fecven){

        $query = Consumo_medicamentos_pacientes_renglones::find()->where(['VA_CODMON' => $codmon,
                                                             'VA_DEPOSITO' => $deposito,
                                                             'VA_FECVTO' => $fecven,
                                                             'VA_NROVALE' => $this->DE_NUMVALOR]);
        $renglon_entrega = $query->one(); 
        
        $query = Devolucion_salas_paciente::find()->where(['DE_NUMVALOR'=>$this->DE_NUMVALOR]);
        $query->joinWith(['renglones']);
        $query->where(['DV_CODMON' => $codmon,'DV_DEPOSITO' => $deposito,'DV_FECVTO' => $fecven]);

        $cantidad_devuelta = $query->sum('DV_CANTID');

        if (isset($cantidad_devuelta))
            $cantidad_entregada = $renglon_entrega->VA_CANTID-$cantidad_devuelta;
        else
            $cantidad_entregada = $renglon_entrega->VA_CANTID;

        return $cantidad_entregada;
    }

    private function existe_articulo($codmon,$deposito){

        if (($model = ArticGral::findOne(['AG_CODIGO' => $codmon, 'AG_DEPOSITO' => $deposito])) !== null) {
            return true;
        } else {
            return false;
        }
    }
}

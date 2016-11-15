<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "vale_enf".
 *
 * @property integer $VE_NUMVALE
 * @property integer $VE_HISCLI
 * @property string $VE_FECHA
 * @property string $VE_HORA
 * @property string $VE_MEDICO
 * @property string $VE_SUPERV
 * @property string $VE_SERSOL
 * @property string $VE_UDSOL
 * @property string $VE_CONDPAC
 * @property string $VE_IDINTERNA
 * @property string $VE_DEPOSITO
 * @property integer $VE_PROCESADO
 *
 * @property VaenRen[] $vaenRens
 * @property Deposito $vEDEPOSITO
 * @property Interna $vEIDINTERNA
 * @property Legajos $vESUPERV
 * @property Legajos $vEMEDICO
 * @property Paciente $vEHISCLI
 * @property Servicio $vEUDSOL
 * @property Servicio $vESERSOL
 */
class ValeEnfermeria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vale_enf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VE_HISCLI', 'VE_IDINTERNA', 'VE_PROCESADO'], 'integer'],
            [['VE_FECHA', 'VE_HORA', 'VE_MEDICO', 'VE_SUPERV', 'VE_CONDPAC', 'VE_PROCESADO'], 'required'],
            [['VE_FECHA', 'VE_HORA'], 'safe'],
            [['VE_CONDPAC'], 'string'],
            [['VE_MEDICO', 'VE_SUPERV'], 'string', 'max' => 6],
            [['VE_SERSOL', 'VE_UDSOL'], 'string', 'max' => 3],
            [['VE_DEPOSITO'], 'string', 'max' => 2],
            [['VE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['VE_DEPOSITO' => 'DE_CODIGO']],
            [['VE_IDINTERNA'], 'exist', 'skipOnError' => true, 'targetClass' => Interna::className(), 'targetAttribute' => ['VE_IDINTERNA' => 'IN_ID']],
            [['VE_SUPERV'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['VE_SUPERV' => 'LE_NUMLEGA']],
            [['VE_MEDICO'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['VE_MEDICO' => 'LE_NUMLEGA']],
            [['VE_HISCLI'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::className(), 'targetAttribute' => ['VE_HISCLI' => 'PA_HISCLI']],
            [['VE_UDSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['VE_UDSOL' => 'SE_CODIGO']],
            [['VE_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['VE_SERSOL' => 'SE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VE_NUMVALE' => 'Número',
            'VE_HISCLI' => 'Historia Clínica',
            'VE_FECHA' => 'Fecha',
            'VE_HORA' => 'Hora',
            'VE_MEDICO' => 'Médico',
            'VE_SUPERV' => 'Supervisor',
            'VE_SERSOL' => 'Servicio',
            'VE_UDSOL' => 'Unidad de Diasgnostico Solicitante',
            'VE_CONDPAC' => 'Tipo Paciente',
            'VE_IDINTERNA' => 'Internación',
            'VE_DEPOSITO' => 'Depósito',
            'VE_PROCESADO' => 'Procesado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVaenRens()
    {
        return $this->hasMany(VaenRen::className(), ['VE_NUMVALE' => 'VE_NUMVALE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVEDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'VE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInterna()
    {
        return $this->hasOne(Interna::className(), ['IN_ID' => 'VE_IDINTERNA']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVESUPERV()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'VE_SUPERV']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedico()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'VE_MEDICO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaciente()
    {
        return $this->hasOne(Paciente::className(), ['PA_HISCLI' => 'VE_HISCLI']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVEUDSOL()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'VE_UDSOL']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVESERSOL()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'VE_SERSOL']);
    }

    public function getInternaHabitacionCama()
    {   
        if (isset($this->interna)) 
            return $this->interna->IN_NUMHAB." - ".$this->interna->IN_NUMCAM;
        else
            return '';
    }
}

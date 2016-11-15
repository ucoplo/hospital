<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "mov_sala".
 *
 * @property integer $MO_HISCLI
 * @property string $MO_CODSERV
 * @property string $MO_FECHA
 * @property string $MO_HORA
 * @property string $MO_DEPOSITO
 * @property string $MO_CODMON
 * @property string $MO_CANT
 * @property string $MO_TIPMOV
 * @property string $MO_ORDEN
 * @property string $MO_SUPOPE
 *
 * @property ArticGral $mOCODMON
 * @property Deposito $mODEPOSITO
 * @property Paciente $mOHISCLI
 * @property Servicio $mOCODSERV
 * @property Movstosa $mOTIPMOV
 */
class Movimientos_sala extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mov_sala';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MO_HISCLI'], 'integer'],
            [['MO_CODSERV', 'MO_FECHA', 'MO_DEPOSITO', 'MO_CODMON', 'MO_TIPMOV'], 'required'],
            [['MO_FECHA', 'MO_HORA'], 'safe'],
            [['MO_CANT'], 'number'],
            [['MO_CODSERV'], 'string', 'max' => 3],
            [['MO_DEPOSITO', 'MO_ORDEN'], 'string', 'max' => 2],
            [['MO_CODMON'], 'string', 'max' => 4],
            [['MO_TIPMOV'], 'string', 'max' => 1],
            [['MO_SUPOPE'], 'string', 'max' => 6],
            [['MO_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['MO_CODMON' => 'AG_CODIGO']],
            [['MO_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['MO_DEPOSITO' => 'DE_CODIGO']],
            [['MO_HISCLI'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::className(), 'targetAttribute' => ['MO_HISCLI' => 'PA_HISCLI']],
            [['MO_CODSERV'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['MO_CODSERV' => 'SE_CODIGO']],
            [['MO_TIPMOV'], 'exist', 'skipOnError' => true, 'targetClass' => Tipo_movimientos_sala::className(), 'targetAttribute' => ['MO_TIPMOV' => 'MS_COD']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MO_HISCLI' => 'Historia Clínica',
            'MO_CODSERV' => 'Sercvicio',
            'MO_FECHA' => 'Fecha',
            'MO_HORA' => 'Hora',
            'MO_DEPOSITO' => 'Depósito',
            'MO_CODMON' => 'Medicamento',
            'MO_CANT' => 'Cantidad',
            'MO_TIPMOV' => 'Tipo Movimiento Sala',
            'MO_ORDEN' => 'Ordenamiento para la ficha de ordenamiento',
            'MO_SUPOPE' => 'Dependiendo del tipo de mov Supervisor o Personal de enfermería',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMOCODMON()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'MO_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMODEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'MO_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMOHISCLI()
    {
        return $this->hasOne(Paciente::className(), ['PA_HISCLI' => 'MO_HISCLI']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMOCODSERV()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'MO_CODSERV']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMOTIPMOV()
    {
        return $this->hasOne(Tipo_movimientos_sala::className(), ['MS_COD' => 'MO_TIPMOV']);
    }
}

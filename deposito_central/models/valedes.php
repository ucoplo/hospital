<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vale_des".
 *
 * @property string $VD_SERSOL
 * @property integer $VD_NUMVALE
 * @property string $VD_FECHA
 * @property string $VD_HORA
 * @property string $VD_SUPERV
 * @property string $VD_DEPOSITO
 * @property integer $VD_PROCESADO
 *
 * @property PlanEnt[] $planEnts
 * @property VadeRen[] $vadeRens
 * @property Deposito $vDDEPOSITO
 * @property Legajos $vDSUPERV
 * @property Servicio $vDSERSOL
 */
class valedes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vale_des';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VD_FECHA', 'VD_HORA'], 'safe'],
            [['VD_PROCESADO'], 'integer'],
            [['VD_SERSOL'], 'string', 'max' => 3],
            [['VD_SUPERV'], 'string', 'max' => 6],
            [['VD_DEPOSITO'], 'string', 'max' => 2],
            [['VD_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['VD_DEPOSITO' => 'DE_CODIGO']],
            [['VD_SUPERV'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['VD_SUPERV' => 'LE_NUMLEGA']],
            [['VD_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['VD_SERSOL' => 'SE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VD_SERSOL' => 'Servicio',
            'VD_NUMVALE' => 'Número',
            'VD_FECHA' => 'Fecha',
            'VD_HORA' => 'Hora',
            'VD_SUPERV' => 'Personal Enfermería',
            'VD_DEPOSITO' => 'Depósito',
            'VD_PROCESADO' => 'Procesado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanEnts()
    {
        return $this->hasMany(PlanEnt::className(), ['PE_NUMVALE' => 'VD_NUMVALE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVadeRens()
    {
        return $this->hasMany(VadeRen::className(), ['VD_NUMVALE' => 'VD_NUMVALE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVDDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'VD_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVDSUPERV()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'VD_SUPERV']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVDSERSOL()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'VD_SERSOL']);
    }
}

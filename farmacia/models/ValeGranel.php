<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "vale_mon".
 *
 * @property string $VM_SERSOL
 * @property integer $VM_NUMVALE
 * @property string $VM_FECHA
 * @property string $VM_HORA
 * @property string $VM_SUPERV
 * @property string $VM_DEPOSITO
 * @property integer $VM_PROCESADO
 *
 * @property Deposito $vMDEPOSITO
 * @property Legajos $vMSUPERV
 * @property Servicio $vMSERSOL
 * @property VamoRen[] $vamoRens
 */
class ValeGranel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vale_mon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VM_FECHA', 'VM_HORA'], 'safe'],
            [['VM_PROCESADO'], 'integer'],
            [['VM_SERSOL'], 'string', 'max' => 3],
            [['VM_SUPERV'], 'string', 'max' => 6],
            [['VM_DEPOSITO'], 'string', 'max' => 2],
            [['VM_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['VM_DEPOSITO' => 'DE_CODIGO']],
            [['VM_SUPERV'], 'exist', 'skipOnError' => true, 'targetClass' => Legajos::className(), 'targetAttribute' => ['VM_SUPERV' => 'LE_NUMLEGA']],
            [['VM_SERSOL'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['VM_SERSOL' => 'SE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VM_SERSOL' => 'Servicio',
            'VM_NUMVALE' => 'Número',
            'VM_FECHA' => 'Fecha',
            'VM_HORA' => 'Hora',
            'VM_SUPERV' => 'Personal Enfermería',
            'VM_DEPOSITO' => 'Depósito',
            'VM_PROCESADO' => 'Procesado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVMDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'VM_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVMSUPERV()
    {
        return $this->hasOne(Legajos::className(), ['LE_NUMLEGA' => 'VM_SUPERV']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVMSERSOL()
    {
        return $this->hasOne(Servicio::className(), ['SE_CODIGO' => 'VM_SERSOL']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenglones()
    {
        return $this->hasMany(ValeGranelRenglones::className(), ['VM_NUMVALE' => 'VM_NUMVALE']);
    }
}

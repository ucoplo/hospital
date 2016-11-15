<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "vaen_ren".
 *
 * @property integer $VE_NUMVALE
 * @property integer $VE_NUMRENG
 * @property string $VE_CODMON
 * @property string $VE_DEPOSITO
 * @property string $VE_CANTID
 *
 * @property ArticGral $monodroga
 * @property Deposito $vEDEPOSITO
 * @property ValeEnf $vENUMVALE
 */
class ValeEnfermeriaRenglones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vaen_ren';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VE_NUMVALE', 'VE_NUMRENG', 'VE_CODMON', 'VE_DEPOSITO', 'VE_CANTID'], 'required'],
            [['VE_NUMVALE', 'VE_NUMRENG'], 'integer'],
            [['VE_CANTID'], 'number'],
            [['VE_CODMON'], 'string', 'max' => 4],
            [['VE_DEPOSITO'], 'string', 'max' => 2],
            [['VE_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['VE_CODMON' => 'AG_CODIGO']],
            [['VE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['VE_DEPOSITO' => 'DE_CODIGO']],
            [['VE_NUMVALE'], 'exist', 'skipOnError' => true, 'targetClass' => ValeEnfermeria::className(), 'targetAttribute' => ['VE_NUMVALE' => 'VE_NUMVALE']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VE_NUMVALE' => 'Número Vale',
            'VE_NUMRENG' => 'Renglón',
            'VE_CODMON' => 'Medicamento',
            'VE_DEPOSITO' => 'Depósito',
            'VE_CANTID' => 'Cantidad pedida',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'VE_CODMON']);
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
    public function getVale()
    {
        return $this->hasOne(ValeEnfermeria::className(), ['VE_NUMVALE' => 'VE_NUMVALE']);
    }

    public function getPaciente()
    {
        return $this->hasOne(Paciente::className(), ['PA_HISCLI' => 'VE_HISCLI'])
                     ->via('vale');
    }
    
}

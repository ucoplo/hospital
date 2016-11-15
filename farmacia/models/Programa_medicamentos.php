<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "prog_med".
 *
 * @property string $PM_CODPROG
 * @property string $PM_DEPOSITO
 * @property string $PM_CODMON
 * @property string $PM_CANTENT
 *
 * @property ArticGral $pMCODMON
 * @property Deposito $pMDEPOSITO
 * @property Programa $pMCODPROG
 */
class Programa_medicamentos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prog_med';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PM_CODPROG', 'PM_DEPOSITO', 'PM_CODMON'], 'required'],
            [['PM_CANTENT'], 'number'],
            [['PM_CODPROG', 'PM_DEPOSITO'], 'string', 'max' => 2],
            [['PM_CODMON'], 'string', 'max' => 4],
            [['PM_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['PM_CODMON' => 'AG_CODIGO']],
            [['PM_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PM_DEPOSITO' => 'DE_CODIGO']],
            [['PM_CODPROG'], 'exist', 'skipOnError' => true, 'targetClass' => Programa::className(), 'targetAttribute' => ['PM_CODPROG' => 'PR_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PM_CODPROG' => 'Programa',
            'PM_DEPOSITO' => 'Depósito',
            'PM_CODMON' => 'Medicamento',
            'PM_CANTENT' => 'Cantidad a entregar',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPMCODMON()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'PM_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPMDEPOSITO()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'PM_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPMCODPROG()
    {
        return $this->hasOne(Programa::className(), ['PR_CODIGO' => 'PM_CODPROG']);
    }
}

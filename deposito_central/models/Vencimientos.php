<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "tab_vtos".
 *
 * @property string $DT_CODART
 * @property string $DT_FECVEN
 * @property string $DT_SALDO
 * @property string $DT_DEPOSITO
 *
 * @property ArticGral $tVCODART
 * @property Deposito $tVDEPOSITO
 */
class Vencimientos extends \yii\db\ActiveRecord
{   
    public $consumo_medio;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dc_tab_vtos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DT_CODART', 'DT_FECVEN', 'DT_DEPOSITO'], 'required'],
            [['DT_FECVEN'], 'safe'],
            [['DT_SALDO'], 'number'],
            [['DT_CODART'], 'string', 'max' => 4],
            [['DT_DEPOSITO'], 'string', 'max' => 2],
            [['DT_CODART'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['DT_CODART' => 'AG_CODIGO']],
            [['DT_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DT_DEPOSITO' => 'DE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DT_CODART' => 'Código del artículo',
            'DT_FECVEN' => 'Fecha de vencimiento',
            'DT_SALDO' => 'Cantidad',
            'DT_DEPOSITO' => 'Depósito',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'DT_CODART','AG_DEPOSITO' => 'DT_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DT_DEPOSITO']);
    }
}

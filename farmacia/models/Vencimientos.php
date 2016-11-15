<?php

namespace farmacia\models;

use Yii;

/**
 * This is the model class for table "tab_vtos".
 *
 * @property string $TV_CODART
 * @property string $TV_FECVEN
 * @property string $TV_SALDO
 * @property string $TV_DEPOSITO
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
        return 'tab_vtos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TV_CODART', 'TV_FECVEN', 'TV_DEPOSITO'], 'required'],
            [['TV_FECVEN'], 'safe'],
            [['TV_SALDO'], 'number'],
            [['TV_CODART'], 'string', 'max' => 4],
            [['TV_DEPOSITO'], 'string', 'max' => 2],
            [['TV_CODART'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['TV_CODART' => 'AG_CODIGO']],
            [['TV_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['TV_DEPOSITO' => 'DE_CODIGO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TV_CODART' => 'Código del artículo',
            'TV_FECVEN' => 'Fecha de vencimiento',
            'TV_SALDO' => 'Cantidad',
            'TV_DEPOSITO' => 'Depósito',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'TV_CODART']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'TV_DEPOSITO']);
    }
}

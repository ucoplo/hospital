<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "vade_ren".
 *
 * @property integer $VD_NUMVALE
 * @property integer $VD_NUMRENG
 * @property string $VD_CODMON
 * @property string $VD_DEPOSITO
 * @property string $VD_CANTID
 *
 * @property ArticGral $vMCODMON
 * @property Deposito $vMDEPOSITO
 * @property ValeMon $vMNUMVALE
 */
class PedidoInsumosRenglones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vade_ren';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VD_NUMVALE', 'VD_NUMRENG', 'VD_CODMON', 'VD_DEPOSITO', 'VD_CANTID'], 'required'],
            [['VD_NUMVALE', 'VD_NUMRENG'], 'integer'],
            [['VD_CANTID'], 'number'],
            ['VD_CANTID', 'compare', 'compareValue' => 1, 'operator' => '>='],
            [['VD_CODMON'], 'string', 'max' => 4],
            [['VD_DEPOSITO'], 'string', 'max' => 2],
            [['VD_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['VD_CODMON' => 'AG_CODIGO']],
            [['VD_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['VD_DEPOSITO' => 'DE_CODIGO']],
            [['VD_NUMVALE'], 'exist', 'skipOnError' => true, 'targetClass' => PedidoInsumos::className(), 'targetAttribute' => ['VD_NUMVALE' => 'VD_NUMVALE']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VD_NUMVALE' => 'Número de vale',
            'VD_NUMRENG' => 'Número de renglón',
            'VD_CODMON' => 'Código Artículo',
            'VD_DEPOSITO' => 'Depósito',
            'VD_CANTID' => 'Cantidad pedida',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticulo()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'VD_CODMON','AG_DEPOSITO' => 'VD_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'VD_DEPOSITO']);
    }
}

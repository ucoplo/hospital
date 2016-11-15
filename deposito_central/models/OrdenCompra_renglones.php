<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "reng_oc".
 *
 * @property string $EN_NROOC
 * @property integer $EN_ITEM
 * @property string $EN_CODART
 * @property string $EN_DEPOSITO
 * @property double $EN_CANT
 * @property double $EN_COSTO
 *
 * @property ArticGral $eNCODART
 * @property OrdenesCompra $eNNROOC
 */
class OrdenCompra_renglones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reng_oc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EN_NROOC', 'EN_ITEM'], 'required'],
            [['EN_ITEM'], 'integer'],
            [['EN_CANT', 'EN_COSTO'], 'number'],
            [['EN_NROOC'], 'string', 'max' => 10],
            [['EN_CODART'], 'string', 'max' => 4],
            [['EN_DEPOSITO'], 'string', 'max' => 2],
            [['EN_CODART', 'EN_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['EN_CODART' => 'AG_CODIGO', 'EN_DEPOSITO' => 'AG_DEPOSITO']],
            [['EN_NROOC'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenCompra::className(), 'targetAttribute' => ['EN_NROOC' => 'OC_NRO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'EN_NROOC' => 'En  Nrooc',
            'EN_ITEM' => 'En  Item',
            'EN_CODART' => 'En  Codart',
            'EN_DEPOSITO' => 'En  Deposito',
            'EN_CANT' => 'En  Cant',
            'EN_COSTO' => 'En  Costo',
            'EN_CODRAFAM' => 'Código Rafam',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticulo()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'EN_CODART', 'AG_DEPOSITO' => 'EN_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrden_compra()
    {
        return $this->hasOne(OrdenCompra::className(), ['OC_NRO' => 'EN_NROOC']);
    }
}

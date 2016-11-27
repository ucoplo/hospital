<?php

namespace deposito_central\models;

use Yii;

/**
 * This is the model class for table "peen_mov".
 *
 * @property string $PE_ID
 * @property integer $PE_NROPED
 * @property integer $PE_NRORENG
 * @property string $PE_CODMON
 * @property string $PE_DEPOSITO
 * @property string $PE_CANTPED
 * @property string $PE_CANTENT
 *
 * @property ArticGral $pECODMON
 * @property Pedentre $pENROPED
 */
class PedidosReposicionFarmacia_renglones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'peen_mov';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PE_NROPED', 'PE_NRORENG', 'PE_CODMON', 'PE_DEPOSITO', 'PE_CANTPED'], 'required'],
            [['PE_NROPED', 'PE_NRORENG'], 'integer'],
            [['PE_CANTPED', 'PE_CANTENT'], 'number'],
            [['PE_CODMON'], 'string', 'max' => 4],
            [['PE_DEPOSITO'], 'string', 'max' => 2],
            [['PE_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['PE_CODMON' => 'AG_CODIGO','PE_DEPOSITO' => 'AG_DEPOSITO']],
            [['PE_NROPED'], 'exist', 'skipOnError' => true, 'targetClass' => PedidosReposicionFarmacia::className(), 'targetAttribute' => ['PE_NROPED' => 'PE_NROPED']],
            [['PE_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PE_DEPOSITO' => 'DE_CODIGO']],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PE_ID' => 'Pe  ID',
            'PE_NROPED' => 'Número Pedido',
            'PE_NRORENG' => 'Número de renglón',
            'PE_CODMON' => 'Monodroga',
            'PE_DEPOSITO' => 'Depósito',
            'PE_CANTPED' => 'Cantidad pedida',
            'PE_CANTENT' => 'Cantidad entregada',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticulo()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'PE_CODMON','AG_DEPOSITO' => 'PE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'PE_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedido()
    {
        return $this->hasOne(PedidosReposicionFarmacia::className(), ['PE_NROPED' => 'PE_NROPED']);
    }
}

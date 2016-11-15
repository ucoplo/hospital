<?php

namespace deposito_central\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "planfar".
 *
 * @property integer $PR_NROREM
 * @property string $PR_CODART
 * @property string $PR_DEPOSITO
 * @property string $PR_CANTID
 * @property string $PR_FECVTO
 *
 * @property ArticGral $pFCODMON
 * @property Consme3 $pFNROREM
 * @property Deposito $pFDEPOSITO
 * @property TabVtos $pFCODMON0
 */
class Planilla_entrega_renglones extends \yii\db\ActiveRecord
{
    public $descripcion;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pe_reng';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PR_NROREM', 'PR_CODART', 'PR_DEPOSITO', 'PR_CANTID', 'PR_FECVTO'], 'required'],
            [['PR_NROREM'], 'integer'],
            [['PR_CANTID'], 'number'],
            [['PR_FECVTO'], 'safe'],
            [['PR_CODART'], 'string', 'max' => 4],
            [['PR_DEPOSITO'], 'string', 'max' => 2],
            [['PR_CODART'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['PR_CODART' => 'AG_CODIGO','PR_DEPOSITO' => 'AG_DEPOSITO']],
            [['PR_NROREM'], 'exist', 'skipOnError' => true, 'targetClass' => Planilla_entrega::className(), 'targetAttribute' => ['PR_NROREM' => 'PE_NROREM']],
            [['PR_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PR_DEPOSITO' => 'DE_CODIGO']],
            [['PR_CODART', 'PR_FECVTO', 'PR_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Vencimientos::className(), 'targetAttribute' => ['PR_CODART' => 'DT_CODART', 'PR_FECVTO' => 'DT_FECVEN', 'PR_DEPOSITO' => 'DT_DEPOSITO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PR_NROREM' => 'Número de remito',
            'PR_CODART' => 'Código del artículo',
            'PR_DEPOSITO' => 'Depósito',
            'PR_CANTID' => 'Cantidad entregada',
            'PR_FECVTO' => 'Fecha de vencimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticulo()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'PR_CODART','AG_DEPOSITO' => 'PR_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemito()
    {
        return $this->hasOne(Planilla_entrega::className(), ['PE_NROREM' => 'PR_NROREM']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'PR_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPFCODMON0()
    {
        return $this->hasOne(Vencimientos::className(), ['DT_CODART' => 'PR_CODART', 'DT_FECVEN' => 'PR_FECVTO', 'DT_DEPOSITO' => 'PR_DEPOSITO']);
    }

    public function get_renglones($id=0)
    {
        $query = Planilla_entrega_renglones::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'PR_NROREM' => $id,
        ]);

        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
}

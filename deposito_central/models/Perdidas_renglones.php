<?php

namespace deposito_central\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "perdfar".
 *
 * @property integer $DR_NROREM
 * @property string $DR_DEPOSITO
 * @property string $DR_CODART
 * @property string $DR_CANTID
 * @property string $DR_FECVTO
 *
 * @property ArticGral $pFCODMON
 * @property Deposito $pFDEPOSITO
 * @property Perdidas $pFNROREM
 */
class Perdidas_renglones extends \yii\db\ActiveRecord
{   
    public $descripcion;
    public $vencimientos;
    public $cantidad,$valor;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dc_perd_reng';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DR_CANTID'], 'required'],
            [['DR_NROREM'], 'integer'],
            [['DR_CANTID'], 'number'],
            [['DR_FECVTO'], 'safe'],
            [['DR_DEPOSITO'], 'string', 'max' => 2],
            [['DR_CODART'], 'string', 'max' => 4],
            [['DR_CODART'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['DR_CODART' => 'AG_CODIGO']],
            [['DR_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['DR_DEPOSITO' => 'DE_CODIGO']],
            [['DR_NROREM'], 'exist', 'skipOnError' => true, 'targetClass' => Perdidas::className(), 'targetAttribute' => ['DR_NROREM' => 'DP_NROREM']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DR_NROREM' => 'Número de Remito Pérdida',
            'DR_DEPOSITO' => 'Subdepósito',
            'DR_CODART' => 'Código del artículo ',
            'DR_CANTID' => 'Cantidad',
            'DR_FECVTO' => 'Fecha de vencimiento del artículo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticulo()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'DR_CODART','AG_DEPOSITO' => 'DR_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'DR_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemito()
    {
        return $this->hasOne(Perdidas::className(), ['DP_NROREM' => 'DR_NROREM']);
    }

    //Obtiene todos los renglones de un Remito de Pérdida
    public function get_renglones($id=0)
    {
        $query = Perdidas_renglones::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'DR_NROREM' => $id,
        ]);

        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
}


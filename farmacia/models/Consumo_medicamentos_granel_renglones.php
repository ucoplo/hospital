<?php

namespace farmacia\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "planfar".
 *
 * @property integer $PF_NROREM
 * @property string $PF_CODMON
 * @property string $PF_DEPOSITO
 * @property string $PF_CANTID
 * @property string $PF_FECVTO
 *
 * @property ArticGral $pFCODMON
 * @property Consme3 $pFNROREM
 * @property Deposito $pFDEPOSITO
 * @property TabVtos $pFCODMON0
 */
class Consumo_medicamentos_granel_renglones extends \yii\db\ActiveRecord
{
    public $descripcion;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'planfar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PF_NROREM', 'PF_CODMON', 'PF_DEPOSITO', 'PF_CANTID', 'PF_FECVTO'], 'required'],
            [['PF_NROREM'], 'integer'],
            [['PF_CANTID'], 'number'],
            [['PF_FECVTO'], 'safe'],
            [['PF_CODMON'], 'string', 'max' => 4],
            [['PF_DEPOSITO'], 'string', 'max' => 2],
            [['PF_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['PF_CODMON' => 'AG_CODIGO']],
            [['PF_NROREM'], 'exist', 'skipOnError' => true, 'targetClass' => Consumo_medicamentos_granel::className(), 'targetAttribute' => ['PF_NROREM' => 'CM_NROREM']],
            [['PF_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PF_DEPOSITO' => 'DE_CODIGO']],
            [['PF_CODMON', 'PF_FECVTO', 'PF_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Vencimientos::className(), 'targetAttribute' => ['PF_CODMON' => 'TV_CODART', 'PF_FECVTO' => 'TV_FECVEN', 'PF_DEPOSITO' => 'TV_DEPOSITO']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PF_NROREM' => 'Número de remito',
            'PF_CODMON' => 'Código del medicamento',
            'PF_DEPOSITO' => 'Depósito',
            'PF_CANTID' => 'Cantidad entregada',
            'PF_FECVTO' => 'Fecha de vencimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonodroga()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'PF_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemito()
    {
        return $this->hasOne(Consumo_medicamentos_granel::className(), ['CM_NROREM' => 'PF_NROREM']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'PF_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPFCODMON0()
    {
        return $this->hasOne(Vencimientos::className(), ['TV_CODART' => 'PF_CODMON', 'TV_FECVEN' => 'PF_FECVTO', 'TV_DEPOSITO' => 'PF_DEPOSITO']);
    }

    public function get_renglones($id=0)
    {
        $query = Consumo_medicamentos_granel_renglones::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'PF_NROREM' => $id,
        ]);

        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
}

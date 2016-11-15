<?php

namespace farmacia\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "perdfar".
 *
 * @property integer $PF_NROREM
 * @property string $PF_DEPOSITO
 * @property string $PF_CODMON
 * @property string $PF_CANTID
 * @property string $PF_FECVTO
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
        return 'perdfar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PF_NROREM'], 'integer'],
            [['PF_CANTID'], 'number'],
            [['PF_FECVTO'], 'safe'],
            [['PF_DEPOSITO'], 'string', 'max' => 2],
            [['PF_CODMON'], 'string', 'max' => 4],
            [['PF_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['PF_CODMON' => 'AG_CODIGO']],
            [['PF_DEPOSITO'], 'exist', 'skipOnError' => true, 'targetClass' => Deposito::className(), 'targetAttribute' => ['PF_DEPOSITO' => 'DE_CODIGO']],
            [['PF_NROREM'], 'exist', 'skipOnError' => true, 'targetClass' => Perdidas::className(), 'targetAttribute' => ['PF_NROREM' => 'PE_NROREM']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PF_NROREM' => 'Número de Remito Pérdida',
            'PF_DEPOSITO' => 'Subdepósito de farmacia',
            'PF_CODMON' => 'Código del medicamento ',
            'PF_CANTID' => 'Cantidad',
            'PF_FECVTO' => 'Fecha de vencimiento del medicamento',
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
    public function getDeposito()
    {
        return $this->hasOne(Deposito::className(), ['DE_CODIGO' => 'PF_DEPOSITO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemito()
    {
        return $this->hasOne(Perdidas::className(), ['PE_NROREM' => 'PF_NROREM']);
    }

    //Obtiene todos los renglones de un Remito de Pérdida
    public function get_renglones($id=0)
    {
        $query = Perdidas_renglones::find();

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


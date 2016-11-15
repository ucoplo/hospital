<?php

namespace farmacia\models;
use yii\data\ActiveDataProvider;

use Yii;

/**
 * This is the model class for table "peen_mov".
 *
 * @property string $PE_ID
 * @property integer $PE_NROPED
 * @property integer $PE_NRORENG
 * @property string $PE_CODMED
 * @property string $PE_CANTPED
 * @property string $PE_CANTENT
 *
 * @property ArticGral $pECODMED
 * @property Pedentre $pENROPED
 */
class PeenMov extends \yii\db\ActiveRecord
{
    public $descripcion;
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
            [['PE_NROPED', 'PE_NRORENG', 'PE_CODMON', 'PE_CANTPED'], 'required'],
            [['PE_NROPED', 'PE_NRORENG'], 'integer'],
            [['PE_CANTPED', 'PE_CANTENT'], 'number'],
            [['PE_CODMON'], 'string', 'max' => 4],
            [['PE_CODMON'], 'exist', 'skipOnError' => true, 'targetClass' => ArticGral::className(), 'targetAttribute' => ['PE_CODMON' => 'AG_CODIGO']],
            [['PE_NROPED'], 'exist', 'skipOnError' => true, 'targetClass' => Pedentre::className(), 'targetAttribute' => ['PE_NROPED' => 'PE_NROPED']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PE_ID' => 'id',
            'PE_NROPED' => 'Nro Pedido',
            'PE_NRORENG' => 'Renglón',
            'PE_CODMON' => 'Monodroga',
            'PE_CANTPED' => 'Cantidad pedida',
            'PE_CANTENT' => 'Cantidad entregada',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPECODMON()
    {
        return $this->hasOne(ArticGral::className(), ['AG_CODIGO' => 'PE_CODMON']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPENROPED()
    {
        return $this->hasOne(Pedentre::className(), ['PE_NROPED' => 'PE_NROPED']);
    }

     public function get_renglones($id=0)
    {
        $query = PeenMov::find();

        // add conditions that should always apply here
        $query->andFilterWhere([
            'PE_NROPED' => $id,
        ]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
        ]);

        return $dataProvider;   
    }
}

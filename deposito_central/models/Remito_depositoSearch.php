<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Remito_deposito;

/**
 * Remito_depositoSearch represents the model behind the search form about `deposito_central\models\Remito_deposito`.
 */
class Remito_depositoSearch extends Remito_deposito
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RS_CODEP', 'RS_FECHA', 'RS_HORA', 'RS_CODOPE', 'RS_SERSOL', 'RS_IMPORT'], 'safe'],
            [['RS_NROREM', 'RS_NUMPED'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Remito_deposito::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'RS_NROREM' => $this->RS_NROREM,
            'RS_FECHA' => $this->RS_FECHA,
            'RS_HORA' => $this->RS_HORA,
            'RS_NUMPED' => $this->RS_NUMPED,
        ]);

        $query->andFilterWhere(['like', 'RS_CODEP', $this->RS_CODEP])
            ->andFilterWhere(['like', 'RS_CODOPE', $this->RS_CODOPE])
            ->andFilterWhere(['like', 'RS_SERSOL', $this->RS_SERSOL])
            ->andFilterWhere(['like', 'RS_IMPORT', $this->RS_IMPORT]);

        return $dataProvider;
    }
}

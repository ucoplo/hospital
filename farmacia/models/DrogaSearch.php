<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Droga;

/**
 * DrogaSearch represents the model behind the search form about `farmacia\models\Droga`.
 */
class DrogaSearch extends Droga
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DR_CODIGO', 'DR_DESCRI', 'DR_CLASE'], 'safe'],
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
        $query = Droga::find();

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
        $query->andFilterWhere(['like', 'DR_CODIGO', $this->DR_CODIGO])
            ->andFilterWhere(['like', 'DR_DESCRI', $this->DR_DESCRI])
            ->andFilterWhere(['like', 'DR_CLASE', $this->DR_CLASE]);

        return $dataProvider;
    }
}

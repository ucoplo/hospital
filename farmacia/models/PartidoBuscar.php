<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Partido;

/**
 * PartidoBuscar represents the model behind the search form about `farmacia\models\Partido`.
 */
class PartidoBuscar extends Partido
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PT_COD', 'PT_DETALLE'], 'safe'],
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
        $query = Partido::find();

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
        $query->andFilterWhere(['like', 'PT_COD', $this->PT_COD])
            ->andFilterWhere(['like', 'PT_DETALLE', $this->PT_DETALLE]);

        return $dataProvider;
    }
}

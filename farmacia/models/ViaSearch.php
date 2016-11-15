<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Via;

/**
 * ViaSearch represents the model behind the search form about `farmacia\models\Via`.
 */
class ViaSearch extends Via
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VI_CODIGO', 'VI_DESCRI'], 'safe'],
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
        $query = Via::find();

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
        $query->andFilterWhere(['like', 'VI_CODIGO', $this->VI_CODIGO])
            ->andFilterWhere(['like', 'VI_DESCRI', $this->VI_DESCRI]);

        return $dataProvider;
    }
}

<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Motivo_perdida;
use deposito_central\models\Motivo_perdidaSearch;

/**
 * Motivo_perdidaSearch represents the model behind the search form about `deposito_central\models\Motivo_perdida`.
 */
class Motivo_perdidaSearch extends Motivo_perdida
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MP_COD', 'MP_NOM'], 'safe'],
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
        $query = Motivo_perdida::find();

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
        $query->andFilterWhere(['like', 'MP_COD', $this->MP_COD])
            ->andFilterWhere(['like', 'MP_NOM', $this->MP_NOM]);

        return $dataProvider;
    }
}

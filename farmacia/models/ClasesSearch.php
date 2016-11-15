<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Clases;

/**
 * ClasesSearch represents the model behind the search form about `farmacia\models\Clases`.
 */
class ClasesSearch extends Clases
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CL_COD', 'CL_NOM'], 'safe'],
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
        $query = Clases::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'CL_COD', $this->CL_COD])
            ->andFilterWhere(['like', 'CL_NOM', $this->CL_NOM]);

        return $dataProvider;
    }
}

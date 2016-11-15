<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Labo;

/**
 * LaboSearch represents the model behind the search form about `farmacia\models\Labo`.
 */
class LaboSearch extends Labo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['LA_CODIGO', 'LA_NOMBRE', 'LA_TIPO'], 'safe'],
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
        $query = Labo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'LA_CODIGO', $this->LA_CODIGO])
            ->andFilterWhere(['like', 'UPPER(LA_NOMBRE)', strtoupper ($this->LA_NOMBRE)])
            ->andFilterWhere(['like', 'LA_TIPO', $this->LA_TIPO]);

        return $dataProvider;
    }
}

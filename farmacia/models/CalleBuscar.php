<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Calle;

/**
 * CalleBuscar represents the model behind the search form about `farmacia\models\Calle`.
 */
class CalleBuscar extends Calle
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CA_CODIGO', 'CA_NOM', 'CA_CALLE', 'CA_NACE', 'CA_CORRE', 'CA_DEN_ANT', 'CA_COORDEN', 'CA_OBSERV'], 'safe'],
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
        $query = Calle::find();

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
        $query->andFilterWhere(['like', 'CA_CODIGO', $this->CA_CODIGO])
            ->andFilterWhere(['like', 'CA_NOM', $this->CA_NOM])
            ->andFilterWhere(['like', 'CA_CALLE', $this->CA_CALLE])
            ->andFilterWhere(['like', 'CA_NACE', $this->CA_NACE])
            ->andFilterWhere(['like', 'CA_CORRE', $this->CA_CORRE])
            ->andFilterWhere(['like', 'CA_DEN_ANT', $this->CA_DEN_ANT])
            ->andFilterWhere(['like', 'CA_COORDEN', $this->CA_COORDEN])
            ->andFilterWhere(['like', 'CA_OBSERV', $this->CA_OBSERV]);

        return $dataProvider;
    }
}

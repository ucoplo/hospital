<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Consumo_medicamentos_granel;

/**
 * Consumo_medicamentos_granelSearch represents the model behind the search form about `farmacia\models\Consumo_medicamentos_granel`.
 */
class Consumo_medicamentos_granelSearch extends Consumo_medicamentos_granel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CM_NROREM'], 'integer'],
            [['CM_FECHA', 'CM_HORA', 'CM_SERSOL', 'CM_ENFERM', 'CM_CODOPE', 'CM_DEPOSITO'], 'safe'],
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
        $query = Consumo_medicamentos_granel::find();

        // add conditions that should always apply here
        $query->orderBy(['CM_FECHA'=>SORT_DESC,'CM_HORA'=>SORT_DESC]);

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
            'CM_NROREM' => $this->CM_NROREM,
            'CM_FECHA' => $this->CM_FECHA,
            'CM_HORA' => $this->CM_HORA,
        ]);

        $query->andFilterWhere(['like', 'CM_SERSOL', $this->CM_SERSOL])
            ->andFilterWhere(['like', 'CM_ENFERM', $this->CM_ENFERM])
            ->andFilterWhere(['like', 'CM_CODOPE', $this->CM_CODOPE])
            ->andFilterWhere(['like', 'CM_DEPOSITO', $this->CM_DEPOSITO]);

        return $dataProvider;
    }
}

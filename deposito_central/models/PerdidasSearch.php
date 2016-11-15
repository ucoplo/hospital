<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Perdidas;

/**
 * PerdidasSearch represents the model behind the search form about `deposito_central\models\Perdidas`.
 */
class PerdidasSearch extends Perdidas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DP_NROREM'], 'integer'],
            [['DP_FECHA', 'DP_HORA', 'DP_MOTIVO', 'DP_CODOPE', 'DP_DEPOSITO'], 'safe'],
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
        $query = Perdidas::find();

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
            'DP_NROREM' => $this->DP_NROREM,
            'DP_FECHA' => $this->DP_FECHA,
            'DP_HORA' => $this->DP_HORA,
        ]);

        $query->andFilterWhere(['like', 'DP_MOTIVO', $this->DP_MOTIVO])
            ->andFilterWhere(['like', 'DP_CODOPE', $this->DP_CODOPE])
            ->andFilterWhere(['like', 'DP_DEPOSITO', $this->DP_DEPOSITO]);

        return $dataProvider;
    }
}

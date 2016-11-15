<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Pedentre;

/**
 * Pedidos_adquisicionSearch represents the model behind the search form about `farmacia\models\Pedentre`.
 */
class Pedidos_reposicionSearch extends Pedentre
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PE_NROPED', 'PE_FECHA', 'PE_HORA', 'PE_SERSOL', 'PE_SUPERV', 'PE_PROCESADO'], 'safe'],
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
        $query = Pedentre::find();

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
            'PE_FECHA' => $this->PE_FECHA,
        ]);

        $query->andFilterWhere(['like', 'PE_NROPED', $this->PE_NROPED])
            ->andFilterWhere(['like', 'PE_HORA', $this->PE_HORA])
            ->andFilterWhere(['like', 'PE_SERSOL', $this->PE_SERSOL])
            ->andFilterWhere(['like', 'PE_SUPERV', $this->PE_SUPERV])
            ->andFilterWhere(['=', 'PE_PROCESADO', 'F']);

        return $dataProvider;
    }


}

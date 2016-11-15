<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Planilla_entrega;

/**
 * Planilla_entregaSearch represents the model behind the search form about `deposito_central\models\Planilla_entrega`.
 */
class Planilla_entregaSearch extends Planilla_entrega
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PE_NROREM'], 'integer'],
            [['PE_FECHA', 'PE_HORA', 'PE_SERSOL', 'PE_ENFERM', 'PE_CODOPE', 'PE_DEPOSITO'], 'safe'],
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
        $query = Planilla_entrega::find();

        // add conditions that should always apply here
        $query->orderBy(['PE_FECHA'=>SORT_DESC,'PE_HORA'=>SORT_DESC]);

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
            'PE_NROREM' => $this->PE_NROREM,
            'PE_FECHA' => $this->PE_FECHA,
            'PE_HORA' => $this->PE_HORA,
        ]);

        $query->andFilterWhere(['like', 'PE_SERSOL', $this->PE_SERSOL])
            ->andFilterWhere(['like', 'PE_ENFERM', $this->PE_ENFERM])
            ->andFilterWhere(['like', 'PE_CODOPE', $this->PE_CODOPE])
            ->andFilterWhere(['like', 'PE_DEPOSITO', $this->PE_DEPOSITO]);

        return $dataProvider;
    }
}

<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Perdidas;

/**
 * PerdidasSearch represents the model behind the search form about `farmacia\models\Perdidas`.
 */
class PerdidasSearch extends Perdidas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PE_NROREM'], 'integer'],
            [['PE_FECHA', 'PE_HORA', 'PE_MOTIVO', 'PE_CODOPE', 'PE_DEPOSITO'], 'safe'],
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
            'PE_NROREM' => $this->PE_NROREM,
            'PE_FECHA' => $this->PE_FECHA,
            'PE_HORA' => $this->PE_HORA,
        ]);

        $query->andFilterWhere(['like', 'PE_MOTIVO', $this->PE_MOTIVO])
            ->andFilterWhere(['like', 'PE_CODOPE', $this->PE_CODOPE])
            ->andFilterWhere(['like', 'PE_DEPOSITO', $this->PE_DEPOSITO]);

        return $dataProvider;
    }
}

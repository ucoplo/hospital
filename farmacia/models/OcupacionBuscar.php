<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Ocupacion;

/**
 * OcupacionBuscar represents the model behind the search form about `smu\models\Ocupacion`.
 */
class OcupacionBuscar extends Ocupacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OC_COD', 'OC_DESCRI'], 'safe'],
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
        $query = Ocupacion::find();

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
        $query->andFilterWhere(['like', 'OC_COD', $this->OC_COD])
            ->andFilterWhere(['like', 'OC_DESCRI', $this->OC_DESCRI]);

        return $dataProvider;
    }
}

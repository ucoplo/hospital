<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Alarma;

/**
 * AlarmaSearch represents the model behind the search form about `farmacia\models\Alarma`.
 */
class AlarmaSearch extends Alarma
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AL_CODMON', 'AL_DEPOSITO'], 'safe'],
            [['AL_MIN', 'AL_MAX'], 'integer'],
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
        $query = Alarma::find();

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
            'AL_MIN' => $this->AL_MIN,
            'AL_MAX' => $this->AL_MAX,
        ]);

        $query->andFilterWhere(['like', 'AL_CODMON', $this->AL_CODMON])
            ->andFilterWhere(['like', 'AL_DEPOSITO', $this->AL_DEPOSITO]);

        return $dataProvider;
    }
}

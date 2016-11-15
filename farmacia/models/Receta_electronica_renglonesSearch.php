<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Receta_electronica_renglones;

/**
 * Receta_electronica_renglonesSearch represents the model behind the search form about `farmacia\models\Receta_electronica_renglones`.
 */
class Receta_electronica_renglonesSearch extends Receta_electronica_renglones
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RE_NRORECETA', 'RE_CANTDIA'], 'integer'],
            [['RE_DEPOSITO', 'RE_CODMON', 'RE_INDICACION', 'RE_DIAGNO'], 'safe'],
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
        $query = Receta_electronica_renglones::find();

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
            'RE_NRORECETA' => $this->RE_NRORECETA,
            'RE_CANTDIA' => $this->RE_CANTDIA,
        ]);

        $query->andFilterWhere(['like', 'RE_DEPOSITO', $this->RE_DEPOSITO])
            ->andFilterWhere(['like', 'RE_CODMON', $this->RE_CODMON])
            ->andFilterWhere(['like', 'RE_INDICACION', $this->RE_INDICACION])
            ->andFilterWhere(['like', 'RE_DIAGNO', $this->RE_DIAGNO]);

        return $dataProvider;
    }
}

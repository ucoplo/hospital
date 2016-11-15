<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Receta_electronica;

/**
 * Receta_electronicaSearch represents the model behind the search form about `farmacia\models\Receta_electronica`.
 */
class Receta_electronicaSearch extends Receta_electronica
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RE_NRORECETA', 'RE_HISCLI'], 'integer'],
            [['RE_FECINI', 'RE_FECFIN', 'RE_MEDICO', 'RE_NOTA'], 'safe'],
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
        $query = Receta_electronica::find();

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
            'RE_HISCLI' => $this->RE_HISCLI,
            'RE_FECINI' => $this->RE_FECINI,
            'RE_FECFIN' => $this->RE_FECFIN,
        ]);

        $query->andFilterWhere(['like', 'RE_MEDICO', $this->RE_MEDICO])
            ->andFilterWhere(['like', 'RE_NOTA', $this->RE_NOTA]);

        return $dataProvider;
    }

    public function recetas_vigentes($params)
    {
        $query = Receta_electronica::find();

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
            'RE_HISCLI' => $this->RE_HISCLI,
            'RE_FECINI' => $this->RE_FECINI,
            'RE_FECFIN' => $this->RE_FECFIN,
        ]);

        $query->andFilterWhere(['like', 'RE_MEDICO', $this->RE_MEDICO])
            ->andFilterWhere(['like', 'RE_NOTA', $this->RE_NOTA])
            ->andFilterWhere(['<=', 'RE_FECINI',  date('Y-m-d')])
            ->andFilterWhere(['>=', 'RE_FECFIN',  date('Y-m-d')]);


        return $dataProvider;
    }
    
}

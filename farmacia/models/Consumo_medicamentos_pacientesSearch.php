<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Consumo_medicamentos_pacientes;

/**
 * Consumo_medicamentos_pacientesSearch represents the model behind the search form about `farmacia\models\Consumo_medicamentos_pacientes`.
 */
class Consumo_medicamentos_pacientesSearch extends Consumo_medicamentos_pacientes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CM_NROREM', 'CM_FECHA', 'CM_HORA', 'CM_SERSOL', 'CM_CODOPE', 'CM_UNIDIAG', 'CM_CONDPAC', 'CM_SUPERV', 'CM_MEDICO'], 'safe'],
            [['CM_HISCLI', 'CM_NROVAL'], 'integer'],
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
        $query = Consumo_medicamentos_pacientes::find();
        $query->orderBy(['CM_FECHA'=>SORT_DESC,'CM_NROREM'=>SORT_DESC,'CM_NROVAL'=>SORT_DESC]);
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
            'CM_HISCLI' => $this->CM_HISCLI,
            'CM_FECHA' => $this->CM_FECHA,
            'CM_HORA' => $this->CM_HORA,
            'CM_NROVAL' => $this->CM_NROVAL,
        ]);

        $query->andFilterWhere(['like', 'CM_NROREM', $this->CM_NROREM])
            ->andFilterWhere(['like', 'CM_SERSOL', $this->CM_SERSOL])
            ->andFilterWhere(['like', 'CM_CODOPE', $this->CM_CODOPE])
            ->andFilterWhere(['like', 'CM_UNIDIAG', $this->CM_UNIDIAG])
            ->andFilterWhere(['like', 'CM_CONDPAC', $this->CM_CONDPAC])
            ->andFilterWhere(['like', 'CM_SUPERV', $this->CM_SUPERV])
            ->andFilterWhere(['like', 'CM_MEDICO', $this->CM_MEDICO]);

        return $dataProvider;
    }
}

<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Devolucion_salas_paciente;

/**
 * Devolucion_salas_pacienteSearch represents the model behind the search form about `farmacia\models\Devolucion_salas_paciente`.
 */
class Devolucion_salas_pacienteSearch extends Devolucion_salas_paciente
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DE_NRODEVOL', 'DE_HISCLI', 'DE_NUMVALOR'], 'integer'],
            [['DE_FECHA', 'DE_HORA', 'DE_SERSOL', 'DE_CODOPE', 'DE_ENFERM', 'DE_UNIDIAG', 'DE_DEPOSITO'], 'safe'],
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
        $query = Devolucion_salas_paciente::find();

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
            'DE_NRODEVOL' => $this->DE_NRODEVOL,
            'DE_HISCLI' => $this->DE_HISCLI,
            'DE_FECHA' => $this->DE_FECHA,
            'DE_HORA' => $this->DE_HORA,
            'DE_NUMVALOR' => $this->DE_NUMVALOR,
        ]);

        $query->andFilterWhere(['like', 'DE_SERSOL', $this->DE_SERSOL])
            ->andFilterWhere(['like', 'DE_CODOPE', $this->DE_CODOPE])
            ->andFilterWhere(['like', 'DE_ENFERM', $this->DE_ENFERM])
            ->andFilterWhere(['like', 'DE_UNIDIAG', $this->DE_UNIDIAG])
            ->andFilterWhere(['like', 'DE_DEPOSITO', $this->DE_DEPOSITO]);

        return $dataProvider;
    }
}

<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Movimientos_diarios;

/**
 * Movimientos_diariosSearch represents the model behind the search form about `deposito_central\models\Movimientos_diarios`.
 */
class Movimientos_diariosSearch extends Movimientos_diarios
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DM_FECHA', 'DM_CODMOV', 'DM_FECVTO', 'DM_CODMON', 'DM_DEPOSITO'], 'safe'],
            [['DM_CANT'], 'number'],
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
        $query = Movimientos_diarios::find();

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
            'DM_FECHA' => $this->DM_FECHA,
            'DM_CANT' => $this->DM_CANT,
            'DM_FECVTO' => $this->DM_FECVTO,
        ]);

        $query->andFilterWhere(['like', 'DM_CODMOV', $this->DM_CODMOV])
            ->andFilterWhere(['like', 'DM_CODMON', $this->DM_CODMON])
            ->andFilterWhere(['like', 'DM_DEPOSITO', $this->DM_DEPOSITO]);

        return $dataProvider;
    }
}

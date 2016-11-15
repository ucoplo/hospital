<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Movimientos_diarios;

/**
 * Movimientos_diariosSearch represents the model behind the search form about `farmacia\models\Movimientos_diarios`.
 */
class Movimientos_diariosSearch extends Movimientos_diarios
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MD_FECHA', 'MD_CODMOV', 'MD_FECVEN', 'MD_CODMON', 'MD_DEPOSITO'], 'safe'],
            [['MD_CANT'], 'number'],
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
            'MD_FECHA' => $this->MD_FECHA,
            'MD_CANT' => $this->MD_CANT,
            'MD_FECVEN' => $this->MD_FECVEN,
        ]);

        $query->andFilterWhere(['like', 'MD_CODMOV', $this->MD_CODMOV])
            ->andFilterWhere(['like', 'MD_CODMON', $this->MD_CODMON])
            ->andFilterWhere(['like', 'MD_DEPOSITO', $this->MD_DEPOSITO]);

        return $dataProvider;
    }
}

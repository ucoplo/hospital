<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Servicio;

/**
 * ServicioSearch represents the model behind the search form about `farmacia\models\Servicio`.
 */
class ServicioSearch extends Servicio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SE_CODIGO', 'SE_DESCRI', 'SE_TPOSER', 'SE_CCOSTO', 'SE_SALA', 'SE_AREA', 'SE_INFO'], 'safe'],
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
        $query = Servicio::find();

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
        $query->andFilterWhere(['like', 'SE_CODIGO', $this->SE_CODIGO])
            ->andFilterWhere(['like', 'SE_DESCRI', $this->SE_DESCRI])
            ->andFilterWhere(['like', 'SE_TPOSER', $this->SE_TPOSER])
            ->andFilterWhere(['like', 'SE_CCOSTO', $this->SE_CCOSTO])
            ->andFilterWhere(['like', 'SE_SALA', $this->SE_SALA])
            ->andFilterWhere(['like', 'SE_AREA', $this->SE_AREA])
            ->andFilterWhere(['like', 'SE_INFO', $this->SE_INFO]);

        return $dataProvider;
    }
}

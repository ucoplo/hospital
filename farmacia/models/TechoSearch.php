<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Techo;

/**
 * TechoSearch represents the model behind the search form about `farmacia\models\Techo`.
 */
class TechoSearch extends Techo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_techo'], 'integer'],
            [['TM_CODSERV', 'TM_DEPOSITO', 'TM_CODMON'], 'safe'],
            [['TM_CANTID'], 'number'],
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
        $query = Techo::find();

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
            'id_techo' => $this->id_techo,
            'TM_CANTID' => $this->TM_CANTID,
        ]);

        $query->andFilterWhere(['like', 'TM_CODSERV', $this->TM_CODSERV])
            ->andFilterWhere(['like', 'TM_DEPOSITO', $this->TM_DEPOSITO])
            ->andFilterWhere(['like', 'TM_CODMON', $this->TM_CODMON]);

        return $dataProvider;
    }
}

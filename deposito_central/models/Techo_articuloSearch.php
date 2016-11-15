<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Techo_articulo;

/**
 * Techo_articuloSearch represents the model behind the search form about `deposito_central\models\Techo_articulo`.
 */
class Techo_articuloSearch extends Techo_articulo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TA_CODSERV', 'TA_DEPOSITO', 'TA_CODART'], 'safe'],
            [['TA_CANTID'], 'number'],
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
        $query = Techo_articulo::find();

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
            'TA_CANTID' => $this->TA_CANTID,
        ]);

        $query->andFilterWhere(['like', 'TA_CODSERV', $this->TA_CODSERV])
            ->andFilterWhere(['like', 'TA_DEPOSITO', $this->TA_DEPOSITO])
            ->andFilterWhere(['like', 'TA_CODART', $this->TA_CODART]);

        return $dataProvider;
    }
}

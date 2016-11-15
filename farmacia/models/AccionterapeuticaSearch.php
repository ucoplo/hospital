<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Accionterapeutica;

/**
 * AccionterapeuticaSearch represents the model behind the search form about `farmacia\models\Accionterapeutica`.
 */
class AccionterapeuticaSearch extends Accionterapeutica
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AC_COD', 'AC_DESCRI'], 'safe'],
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
        $query = Accionterapeutica::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'AC_COD', $this->AC_COD])
            ->andFilterWhere(['like', 'AC_DESCRI', $this->AC_DESCRI]);

        return $dataProvider;
    }
}

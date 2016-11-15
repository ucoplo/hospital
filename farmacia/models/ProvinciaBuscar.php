<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Provincia;

/**
 * ProvinciaBuscar represents the model behind the search form about `farmacia\models\Provincia`.
 */
class ProvinciaBuscar extends Provincia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PR_COD', 'PR_DETALLE', 'PR_CODART', 'PR_MODIF', 'PR_CODOP'], 'safe'],
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
        $query = Provincia::find();

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
            'PR_MODIF' => $this->PR_MODIF,
        ]);

        $query->andFilterWhere(['like', 'PR_COD', $this->PR_COD])
            ->andFilterWhere(['like', 'PR_DETALLE', $this->PR_DETALLE])
            ->andFilterWhere(['like', 'PR_CODART', $this->PR_CODART])
            ->andFilterWhere(['like', 'PR_CODOP', $this->PR_CODOP]);

        return $dataProvider;
    }
}

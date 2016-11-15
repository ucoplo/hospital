<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Proveedores;

/**
 * ProveedoresSearch represents the model behind the search form about `deposito_central\models\Proveedores`.
 */
class ProveedoresSearch extends Proveedores
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PR_CODIGO', 'PR_RAZONSOC', 'PR_TITULAR', 'PR_CODRAFAM', 'PR_CUIT', 'PR_DOMIC', 'PR_TELEF', 'PR_EMAIL', 'PR_OBS', 'PR_CONTACTO'], 'safe'],
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
        $query = Proveedores::find();

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
        $query->andFilterWhere(['like', 'PR_CODIGO', $this->PR_CODIGO])
            ->andFilterWhere(['like', 'PR_RAZONSOC', $this->PR_RAZONSOC])
            ->andFilterWhere(['like', 'PR_TITULAR', $this->PR_TITULAR])
            ->andFilterWhere(['like', 'PR_CODRAFAM', $this->PR_CODRAFAM])
            ->andFilterWhere(['like', 'PR_CUIT', $this->PR_CUIT])
            ->andFilterWhere(['like', 'PR_DOMIC', $this->PR_DOMIC])
            ->andFilterWhere(['like', 'PR_TELEF', $this->PR_TELEF])
            ->andFilterWhere(['like', 'PR_EMAIL', $this->PR_EMAIL])
            ->andFilterWhere(['like', 'PR_OBS', $this->PR_OBS])
            ->andFilterWhere(['like', 'PR_CONTACTO', $this->PR_CONTACTO]);

        return $dataProvider;
    }
}

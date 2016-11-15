<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Medic;

/**
 * MedicSearch represents the model behind the search form about `deposito_central\models\Medic`.
 */
class MedicSearch extends Medic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ME_CODIGO', 'ME_NOMCOM', 'ME_CODKAI', 'ME_CODRAF', 'ME_KAIBAR', 'ME_KAITRO', 'ME_CODMON', 'ME_CODLAB', 'ME_PRES', 'ME_FRACCQ', 'ME_ULTCOM', 'ME_ULTSAL', 'ME_RUBRO', 'ME_DEPOSITO'], 'safe'],
            [['ME_VALVEN', 'ME_VALCOM', 'ME_STMIN', 'ME_STMAX', 'ME_UNIENV'], 'number'],
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
        $query = Medic::find();

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
            'ME_VALVEN' => $this->ME_VALVEN,
            'ME_ULTCOM' => $this->ME_ULTCOM,
            'ME_VALCOM' => $this->ME_VALCOM,
            'ME_ULTSAL' => $this->ME_ULTSAL,
            'ME_STMIN' => $this->ME_STMIN,
            'ME_STMAX' => $this->ME_STMAX,
            'ME_UNIENV' => $this->ME_UNIENV,
        ]);

        $query->andFilterWhere(['like', 'ME_CODIGO', $this->ME_CODIGO])
            ->andFilterWhere(['like', 'ME_NOMCOM', $this->ME_NOMCOM])
            ->andFilterWhere(['like', 'ME_CODKAI', $this->ME_CODKAI])
            ->andFilterWhere(['like', 'ME_CODRAF', $this->ME_CODRAF])
            ->andFilterWhere(['like', 'ME_KAIBAR', $this->ME_KAIBAR])
            ->andFilterWhere(['like', 'ME_KAITRO', $this->ME_KAITRO])
            ->andFilterWhere(['like', 'ME_CODMON', $this->ME_CODMON])
            ->andFilterWhere(['like', 'ME_CODLAB', $this->ME_CODLAB])
            ->andFilterWhere(['like', 'ME_PRES', $this->ME_PRES])
            ->andFilterWhere(['like', 'ME_FRACCQ', $this->ME_FRACCQ])
            ->andFilterWhere(['like', 'ME_RUBRO', $this->ME_RUBRO])
            ->andFilterWhere(['like', 'ME_DEPOSITO', $this->ME_DEPOSITO]);

        return $dataProvider;
    }
}

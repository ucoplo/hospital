<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Pedido_adquisicion;

/**
 * Pedido_adquisicionSearch represents the model behind the search form about `deposito_central\models\Pedido_adquisicion`.
 */
class Pedido_adquisicionSearch extends Pedido_adquisicion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PE_NUM', 'PE_EXISACT', 'PE_PEDPEND', 'PE_CLASABC', 'PE_DIASABC', 'PE_DIASPREVIS', 'PE_DIASDEMORA'], 'integer'],
            [['PE_FECHA', 'PE_HORA', 'PE_REFERENCIA', 'PE_NROEXP', 'PE_FECADJ', 'PE_DEPOSITO', 'PE_ARTDES', 'PE_ARTHAS', 'PE_CLASES', 'PE_TIPO','clases'], 'safe'],
            [['PE_COSTO', 'PE_PONDHIS', 'PE_PONDPUN'], 'number'],
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
        $query = Pedido_adquisicion::find();

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
            'PE_NUM' => $this->PE_NUM,
            'PE_FECHA' => $this->PE_FECHA,
            'PE_HORA' => $this->PE_HORA,
            'PE_COSTO' => $this->PE_COSTO,
            'PE_FECADJ' => $this->PE_FECADJ,
            'PE_EXISACT' => $this->PE_EXISACT,
            'PE_PEDPEND' => $this->PE_PEDPEND,
            'PE_PONDHIS' => $this->PE_PONDHIS,
            'PE_PONDPUN' => $this->PE_PONDPUN,
            'PE_CLASABC' => $this->PE_CLASABC,
            'PE_DIASABC' => $this->PE_DIASABC,
            'PE_DIASPREVIS' => $this->PE_DIASPREVIS,
            'PE_DIASDEMORA' => $this->PE_DIASDEMORA,
        ]);

        $query->andFilterWhere(['like', 'PE_REFERENCIA', $this->PE_REFERENCIA])
            ->andFilterWhere(['like', 'PE_NROEXP', $this->PE_NROEXP])
            ->andFilterWhere(['like', 'PE_DEPOSITO', $this->PE_DEPOSITO])
            ->andFilterWhere(['like', 'PE_ARTDES', $this->PE_ARTDES])
            ->andFilterWhere(['like', 'PE_ARTHAS', $this->PE_ARTHAS])
            ->andFilterWhere(['like', 'PE_CLASES', $this->PE_CLASES])
            ->andFilterWhere(['like', 'PE_TIPO', $this->PE_TIPO]);

        return $dataProvider;
    }
}

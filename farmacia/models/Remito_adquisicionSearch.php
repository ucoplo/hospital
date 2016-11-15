<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Remito_adquisicion;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class Remito_adquisicionSearch extends Remito_adquisicion
{
    public $deposito;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RE_NUM', 'RE_REMDEP'], 'integer'],
            [['RE_FECHA', 'RE_HORA', 'RE_CODOPE', 'RE_CONCEP', 'RE_TIPMOV', 'RE_DEPOSITO'], 'safe'],
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
        $query = Remito_adquisicion::find();

        // add conditions that should always apply here
        $query->joinWith(['deposito']);
        $query->orderBy(['RE_FECHA'=>SORT_DESC,'RE_NUM'=>SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

         $dataProvider->sort->attributes['deposito'] = [
            'asc' => ['deposito.DE_DESCR' => SORT_ASC],
            'desc' => ['deposito.DE_DESCR' => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'RE_NUM' => $this->RE_NUM,
            'RE_FECHA' => $this->RE_FECHA,
            'RE_HORA' => $this->RE_HORA,
            'RE_REMDEP' => $this->RE_REMDEP,
        ]);

        $query->andFilterWhere(['like', 'RE_CODOPE', $this->RE_CODOPE])
            ->andFilterWhere(['like', 'RE_CONCEP', $this->RE_CONCEP])
            ->andFilterWhere(['like', 'RE_TIPMOV', $this->RE_TIPMOV])
             ->andFilterWhere(['like', 'deposito.DE_DESCR', $this->deposito]);

        return $dataProvider;
    }
}

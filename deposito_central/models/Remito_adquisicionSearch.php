<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Remito_adquisicion;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `deposito_central\models\Remito_Adquisicion`.
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
            [['RA_NUM', 'RA_OCNRO'], 'integer'],
            [['RA_FECHA', 'RA_HORA', 'RA_CODOPE', 'RA_CONCEP', 'RA_TIPMOV', 'RA_DEPOSITO'], 'safe'],
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
        $query->orderBy(['RA_FECHA'=>SORT_DESC,'RA_NUM'=>SORT_DESC]);
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
            'RA_NUM' => $this->RA_NUM,
            'RA_FECHA' => $this->RA_FECHA,
            'RA_HORA' => $this->RA_HORA,
            'RA_OCNRO' => $this->RA_OCNRO,
        ]);

        $query->andFilterWhere(['like', 'RA_CODOPE', $this->RA_CODOPE])
            ->andFilterWhere(['like', 'RA_CONCEP', $this->RA_CONCEP])
            ->andFilterWhere(['like', 'RA_TIPMOV', $this->RA_TIPMOV])
             ->andFilterWhere(['like', 'deposito.DE_DESCR', $this->deposito]);

        return $dataProvider;
    }

 
}

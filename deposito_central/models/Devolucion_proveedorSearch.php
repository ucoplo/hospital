<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Devolucion_proveedor;

/**
 * Devolucion_proveedorSearch represents the model behind the search form about `deposito_central\models\Devolucion_proveedor`.
 */
class Devolucion_proveedorSearch extends Devolucion_proveedor
{
    public $deposito;
   

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DD_NROREM'], 'integer'],
            [['DD_FECHA', 'DD_HORA', 'DD_PROVE', 'DD_CODOPE', 'DD_DEPOSITO'], 'safe'],
            [['deposito'], 'safe'],
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
        $query = Devolucion_proveedor::find();

        // add conditions that should always apply here
        $query->joinWith(['deposito']);
        $query->orderBy(['DD_FECHA'=>SORT_DESC,'DD_NROREM'=>SORT_DESC]);

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
            'DD_NROREM' => $this->DD_NROREM,
            'DD_FECHA' => $this->DD_FECHA,
            
        ]);

        $query->andFilterWhere(['like', 'DD_CODOPE', $this->DD_CODOPE])
             ->andFilterWhere(['like', 'deposito.DE_DESCR', $this->deposito]);
             


        return $dataProvider;
    }
}

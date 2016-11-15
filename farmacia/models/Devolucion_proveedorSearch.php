<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Devolucion_proveedor;

/**
 * Devolucion_proveedorSearch represents the model behind the search form about `farmacia\models\Devolucion_proveedor`.
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
            [['DE_NROREM'], 'integer'],
            [['DE_FECHA', 'DE_HORA', 'DE_PROVE', 'DE_CODOPE', 'DE_DEPOSITO'], 'safe'],
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
        $query->orderBy(['DE_FECHA'=>SORT_DESC,'DE_NROREM'=>SORT_DESC]);

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
            'DE_NROREM' => $this->DE_NROREM,
            'DE_FECHA' => $this->DE_FECHA,
            
        ]);

        $query->andFilterWhere(['like', 'DE_CODOPE', $this->DE_CODOPE])
             ->andFilterWhere(['like', 'deposito.DE_DESCR', $this->deposito]);
             


        return $dataProvider;
    }
}

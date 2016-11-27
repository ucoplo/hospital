<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\PedidosReposicionFarmacia;

/**
 * Pedido_reposicion_farmaciaSearch represents the model behind the search form about `deposito_central\models\Pedido_reposicion_farmacia`.
 */
class PedidosReposicionFarmaciaSearch extends PedidosReposicionFarmacia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PE_NROPED'], 'integer'],
            [['PE_FECHA', 'PE_HORA', 'PE_SERSOL', 'PE_DEPOSITO', 'PE_REFERENCIA', 'PE_CLASE', 'PE_SUPERV', 'PE_PROCESADO'], 'safe'],
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
        $query = Pedido_reposicion_farmacia::find();

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
            'PE_NROPED' => $this->PE_NROPED,
            'PE_FECHA' => $this->PE_FECHA,
            'PE_HORA' => $this->PE_HORA,
        ]);

        $query->andFilterWhere(['like', 'PE_SERSOL', $this->PE_SERSOL])
            ->andFilterWhere(['like', 'PE_DEPOSITO', $this->PE_DEPOSITO])
            ->andFilterWhere(['like', 'PE_REFERENCIA', $this->PE_REFERENCIA])
            ->andFilterWhere(['like', 'PE_CLASE', $this->PE_CLASE])
            ->andFilterWhere(['like', 'PE_SUPERV', $this->PE_SUPERV])
            ->andFilterWhere(['like', 'PE_PROCESADO', $this->PE_PROCESADO]);

        return $dataProvider;
    }

    public function pedidos_listado()
    {
        $query = (new \yii\db\Query())
        ->select(['PE_SERSOL', 'servicio.SE_DESCRI',
            'legajos.LE_APENOM as supervisor','PE_FECHA','PE_HORA','PE_NROPED'])
        ->from('pedentre')
        ->join('INNER JOIN', 'servicio', 'servicio.SE_CODIGO = pedentre.PE_SERSOL')
        ->join('INNER JOIN', 'legajos', 'legajos.LE_NUMLEGA = pedentre.PE_SUPERV')
        ->where(['PE_PROCESADO' => 'F'])
        ->orderBy(['PE_FECHA'=>SORT_ASC,'PE_HORA'=>SORT_ASC]);  

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}

<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\OrdenCompra;

/**
 * OrdenCompraSearch represents the model behind the search form about `deposito_central\models\OrdenCompra`.
 */
class OrdenCompraSearch extends OrdenCompra
{
    public $numero_oc,$ejercicio_oc;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OC_NRO', 'OC_PROVEED', 'OC_FECHA', 'numero_oc','ejercicio_oc'], 'safe'],
            [['numero_oc','ejercicio_oc'],'required', 'on' => 'seleccion'],
            [['OC_PEDADQ'],'required', 'on' => 'asociar_pedido'],
            [['OC_PEDADQ'],'validateRenglones', 'on' => 'asociar_pedido'],
            [['OC_FINALIZADA', 'OC_PEDADQ'], 'integer'],
        ];
    }

    public function validateRenglones($attribute, $params)
    {   
        $orden_compra = OrdenCompra::findOne($this->OC_NRO);
        $pedido_adqu = Pedido_adquisicion::findOne($this->OC_PEDADQ);
        
        foreach ($orden_compra->renglones as $key => $value) {
          
          $articulo = ArticGral::findOne(['AG_CODRAF'=>$value->EN_CODRAFAM,'AG_DEPOSITO'=>$pedido_adqu->PE_DEPOSITO]);
         
         if (!isset($articulo)){
            $this->addError($attribute, "El artículo $value->EN_CODRAFAM de la Orden de Compra no existe en el Depósito del Pedido seleccionado");
         }
        }
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
        $query = OrdenCompra::find();

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
            'OC_FECHA' => $this->OC_FECHA,
            'OC_FINALIZADA' => $this->OC_FINALIZADA,
            'OC_PEDADQ' => $this->OC_PEDADQ,
        ]);

        $query->andFilterWhere(['like', 'OC_NRO', $this->OC_NRO])
            ->andFilterWhere(['like', 'OC_PROVEED', $this->OC_PROVEED]);

        return $dataProvider;
    }

    public function pedidos_pendientes(){
        $query = Pedido_adquisicion::find();
        $query->joinWith(['renglones']);

        $query->andFilterWhere(['>', 'PE_CANT', 0]
            );

        return $query->all();
    }
}

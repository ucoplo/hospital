<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Numero_remito;

/**
 * Numero_remitoSearch represents the model behind the search form about `farmacia\models\Numero_remito`.
 */
class Numero_remitoSearch extends Numero_remito
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VR_NROREM'], 'integer'],
            [['VR_SERSOL', 'VR_CONDPAC', 'VR_FECDES', 'VR_FECHAS', 'VR_HORDES', 'VR_HORHAS'], 'safe'],
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
        $query = Numero_remito::find();

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
            'VR_NROREM' => $this->VR_NROREM,
            'VR_FECDES' => $this->VR_FECDES,
            'VR_FECHAS' => $this->VR_FECHAS,
            'VR_HORDES' => $this->VR_HORDES,
            'VR_HORHAS' => $this->VR_HORHAS,
        ]);

        $query->andFilterWhere(['like', 'VR_SERSOL', $this->VR_SERSOL])
            ->andFilterWhere(['like', 'VR_CONDPAC', $this->VR_CONDPAC]);

        return $dataProvider;
    }

     public function numero_servicio($servicio,$condpac)
    {
        
        if (($model = Numero_remito::findOne(['VR_SERSOL' => $servicio,'VR_CONDPAC' => $condpac])) !== null) {
            return $model;
        } else {
            $remito= new Numero_remito();
            //$remito->VR_FECDES = date('Y-m-d');
            //$remito->VR_FECHAS = date('Y-m-d');
            $remito->VR_SERSOL = $servicio;
            $remito->VR_CONDPAC = $condpac;
            
            return $remito;

        }
        
        
    }
}

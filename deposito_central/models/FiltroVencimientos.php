<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Remito_adquisicion;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `deposito_central\models\Remito_Adquisicion`.
 */
class FiltroVencimientos extends FiltroReporte
{
   
    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','articulo'], 'safe'],
        ];
    }

    
    public function buscar($params)
    {
        $query = Vencimientos::find();

        $query->joinWith(['articulo']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->attributes['articulo.AG_NOMBRE'] = [
            'asc' => ['AG_NOMBRE' => SORT_ASC],
            'desc' => ['AG_NOMBRE' => SORT_DESC],
        ];

        $this->load($params);

        $query->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
            
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->articulo])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases])
            ->andFilterWhere(['<>','DT_SALDO', 0]);

        return $dataProvider;

    }

    public function buscarVencidos($params)
    {
        $query = Vencimientos::find();

        $query->joinWith(['articulo']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->attributes['articulo.AG_NOMBRE'] = [
            'asc' => ['AG_NOMBRE' => SORT_ASC],
            'desc' => ['AG_NOMBRE' => SORT_DESC],
        ];

        $this->load($params);

        $query->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
            
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->articulo])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases])
            ->andFilterWhere(['<>','DT_SALDO', 0])
            ->andFilterWhere(['<','DT_FECVEN', date('Y-m-d')]);

        return $dataProvider;

    }

    public function buscarPorVencer($params)
    {
        $query = Vencimientos::find();

        $query->joinWith(['articulo']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->attributes['articulo.AG_NOMBRE'] = [
            'asc' => ['AG_NOMBRE' => SORT_ASC],
            'desc' => ['AG_NOMBRE' => SORT_DESC],
        ];

        $this->load($params);

        $query->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
            
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->articulo])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases])
            ->andFilterWhere(['>','DT_SALDO', 0])
            ->andFilterWhere(['>=','DT_FECVEN', date('Y-m-d')]);

        return $dataProvider;

    }

    //Consumo Diario del ultimo año
    public function consumo_medio_diario($codart){
        
        $fecha = date('Y-m-d', strtotime('-1 year'));
        $consumo = 0;

        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $codart,
        ]);
        
        $query1->andFilterWhere(['>=','PE_FECHA', $fecha]);

        $consumo += $query1->sum('PR_CANTID');

       
        $query1 = Devolucion_salas_renglones::find();

        $query1->joinWith(['devolucion_encabezado']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $codart,
        ]);

        $consumo -= $query1->sum('PR_CANTID');

       
        return $consumo/30;

    }
}

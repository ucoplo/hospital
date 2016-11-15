<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Remito_adquisicion;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class VencimientosFiltro extends ReporteFiltro
{
   
    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','monodroga'], 'safe'],
        ];
    }

    
    public function buscar($params)
    {
        $query = Vencimientos::find();

        $query->joinWith(['monodroga']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->attributes['monodroga.AG_NOMBRE'] = [
            'asc' => ['AG_NOMBRE' => SORT_ASC],
            'desc' => ['AG_NOMBRE' => SORT_DESC],
        ];

        $this->load($params);

        $query->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
            
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->monodroga])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases])
            ->andFilterWhere(['<>','TV_SALDO', 0]);

        return $dataProvider;

    }

    public function buscarVencidas($params)
    {
        $query = Vencimientos::find();

        $query->joinWith(['monodroga']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->attributes['monodroga.AG_NOMBRE'] = [
            'asc' => ['AG_NOMBRE' => SORT_ASC],
            'desc' => ['AG_NOMBRE' => SORT_DESC],
        ];

        $this->load($params);

        $query->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
            
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->monodroga])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases])
            ->andFilterWhere(['<>','TV_SALDO', 0])
            ->andFilterWhere(['<','TV_FECVEN', date('Y-m-d')]);

        return $dataProvider;

    }

    public function buscarPorVencer($params)
    {
        $query = Vencimientos::find();

        $query->joinWith(['monodroga']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->attributes['monodroga.AG_NOMBRE'] = [
            'asc' => ['AG_NOMBRE' => SORT_ASC],
            'desc' => ['AG_NOMBRE' => SORT_DESC],
        ];

        $this->load($params);

        $query->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
            
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->monodroga])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases])
            ->andFilterWhere(['>','TV_SALDO', 0])
            ->andFilterWhere(['>=','TV_FECVEN', date('Y-m-d')]);

        return $dataProvider;

    }

    //Consumo Diario del ultimo año
    public function consumo_medio_diario($codmon){
        
        $fecha = date('Y-m-d', strtotime('-1 year'));
        $consumo = 0;

        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $this->deposito,
            'PF_CODMON' => $codmon,
        ]);
        
        $query1->andFilterWhere(['>=','CM_FECHA', $fecha]);

        $consumo += $query1->sum('PF_CANTID');

        $query1 = Consumo_medicamentos_pacientes_renglones::find();
        $query1->joinWith(['vale']);
        $query1->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $codmon,
        ]);
        
        $query1->andFilterWhere(['>=','CM_FECHA', $fecha]);

        $consumo += $query1->sum('VA_CANTID');

        $query1 = Devolucion_salas_granel_renglones::find();

        $query1->joinWith(['devolucion_encabezado']);
        $query1->andFilterWhere([
            'DF_DEPOSITO' => $this->deposito,
            'DF_CODMON' => $codmon,
        ]);

        $consumo -= $query1->sum('DF_CANTID');

        $query1 = Devolucion_salas_paciente_renglones::find();

        $query1->joinWith(['devolucion_encabezado']);
        $query1->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $codmon,
        ]);

        $consumo -= $query1->sum('DV_CANTID');

        return $consumo/30;

    }
}

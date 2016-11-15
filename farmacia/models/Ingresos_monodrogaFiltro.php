<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Remito_adquisicion;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class Ingresos_monodrogaFiltro extends ReporteFiltro
{
   
    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','monodroga','periodo_inicio','periodo_fin'], 'safe'],
        ];
    }

    
    public function buscar($params)
    {
        $query = Remito_adquisicion_renglones::find();

        // add conditions that should always apply here
        $query->joinWith(['remito']);
        $query->joinWith(['monodroga']);

        $query->orderBy(['RM_CODMON'=>SORT_ASC,'RE_FECHA'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'RE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'RE_FECHA', $fecha_fin_format]);
         }


        $query->andFilterWhere(['like', 'RM_CODMON', $this->monodroga])
            ->andFilterWhere(['like', 'RM_DEPOSITO', $this->deposito])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);
  

       
        return $dataProvider;
    }
}

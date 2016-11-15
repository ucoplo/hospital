<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class PerdidasFiltro extends ReporteFiltro
{
    public $activos;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','activos','periodo_inicio','periodo_fin'], 'safe'],
        ];
    }

    
    public function buscar($params)
    {
        $query = Perdidas_renglones::find();

        $query->joinWith(['remito']);
        $query->joinWith(['monodroga']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'PE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'PE_FECHA', $fecha_fin_format]);
         }
        

        $query->andFilterWhere(['like', 'PF_CODMON', $this->monodroga])
            ->andFilterWhere(['like', 'PF_DEPOSITO', $this->deposito]);
            
        $query->groupBy(['PF_CODMON']);

        $query->select(['SUM(PF_CANTID) as cantidad',
                        'SUM(PF_CANTID*AG_PRECIO) as valor',
                        '`perdfar`.*']);

        $query->orderBy(['AG_NOMBRE'=>SORT_DESC]);    
       return $dataProvider;

    }

    
}

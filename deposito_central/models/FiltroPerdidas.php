<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `deposito_central\models\Remito_Adquisicion`.
 */
class FiltroPerdidas extends FiltroReporte
{
    public $activos;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','activos','fecha_inicio','fecha_fin'], 'safe'],
        ];
    }

    
    public function buscar($params)
    {
        $query = Perdidas_renglones::find();

        $query->joinWith(['remito']);
        $query->joinWith(['articulo']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'DP_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'DP_FECHA', $fecha_fin_format]);
         }
        

        $query->andFilterWhere(['like', 'DR_CODART', $this->articulo])
            ->andFilterWhere(['like', 'DR_DEPOSITO', $this->deposito]);
            
        $query->groupBy(['DR_CODART']);

        $query->select(['SUM(DR_CANTID) as cantidad',
                        'SUM(DR_CANTID*AG_PRECIO) as valor',
                        '`dc_perd_reng`.*']);

        $query->orderBy(['AG_NOMBRE'=>SORT_DESC]);    
       return $dataProvider;

    }

    
}

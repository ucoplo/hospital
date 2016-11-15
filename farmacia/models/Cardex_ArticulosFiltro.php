<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class Cardex_articulosFiltro extends ReporteFiltro
{
    

    public function rules()
    {
        return [
            [['deposito','monodroga'], 'required'],
            [['deposito','monodroga','periodo_inicio','periodo_fin'], 'safe'],
        ];
    }

    

    public function buscar($params)
    {
        $query = Movimientos_diarios::find();

        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $this->load($params);

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'MD_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'MD_FECHA', $fecha_fin_format]);
         }
     
        $query->andFilterWhere(['like', 'MD_CODMON', $this->monodroga])
            ->andFilterWhere(['like', 'MD_DEPOSITO', $this->deposito]);

        $query->orderBy(['MD_FECHA'=>SORT_ASC]);    

        return $dataProvider;
       
    }

    public function buscar_lotes($params)
    {
        $query = Movimientos_diarios::find();

        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $this->load($params);
         
         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'MD_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'MD_FECHA', $fecha_fin_format]);
         }
     
        $query->andFilterWhere(['like', 'MD_CODMON', $this->monodroga])
            ->andFilterWhere(['like', 'MD_DEPOSITO', $this->deposito]);

        $query->orderBy(['MD_FECVEN'=>SORT_ASC,'MD_FECHA'=>SORT_ASC]);    

        return $dataProvider;
       
    }

       public function existencia($lote = null){ 
        $existencia = 0;

        if (isset($this->periodo_inicio) && !$this->periodo_inicio==''){
           $movs_anterior = $this->movimientos($lote);
          
           foreach ($movs_anterior as $key => $mov) {
             $existencia += $mov->codigo->MS_SIGNO*$mov->MD_CANT;
           }
        }
        return $existencia;
    }
    public function movimientos($lote)
    {
        $query = Movimientos_diarios::find();

        if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['<', 'MD_FECHA', $fecha_inicio_format]);
         }
     
        $query->andFilterWhere([
          'MD_FECVEN' => $lote,
        ]);


        $query->andFilterWhere(['like', 'MD_CODMON', $this->monodroga])
            ->andFilterWhere(['like', 'MD_DEPOSITO', $this->deposito]);

        $query->orderBy(['MD_FECVEN'=>SORT_ASC]);    

        return $query->all();
    }
   
}

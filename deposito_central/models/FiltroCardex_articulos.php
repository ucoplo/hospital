<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class FiltroCardex_articulos extends FiltroReporte
{
    

    public function rules()
    {
        return [
            [['deposito','articulo'], 'required'],
            [['deposito','articulo','fecha_inicio','fecha_fin'], 'safe'],
            [['articulo'],'validateArticulo'],
        ];
    }

    public function validateArticulo($attribute, $params)
    {
        $codart = $this->articulo;
        $deposito = $this->deposito;
       
        if (!$this->existe_articulo($codart, $deposito)) {
            $key = $attribute;
            $this->addError($key, 'El artículo no existe en el depósito seleccionado');
        }
       
    }
     public function getDeposito_descripcion(){
        return Deposito::findOne($this->deposito)->DE_DESCR;
    }

    public function buscar($params)
    {
        $query = Movimientos_diarios::find();

        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $this->load($params);

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'DM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'DM_FECHA', $fecha_fin_format]);
         }
     
        $query->andFilterWhere(['like', 'DM_CODART', $this->articulo])
            ->andFilterWhere(['like', 'DM_DEPOSITO', $this->deposito]);

        $query->orderBy(['DM_FECHA'=>SORT_ASC]);    

        return $dataProvider;
       
    }

    public function buscar_lotes($params)
    {
        $query = Movimientos_diarios::find();

        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $this->load($params);
         
         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'DM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'DM_FECHA', $fecha_fin_format]);
         }
     
        $query->andFilterWhere(['like', 'DM_CODART', $this->articulo])
            ->andFilterWhere(['like', 'DM_DEPOSITO', $this->deposito]);

        $query->orderBy(['DM_FECVTO'=>SORT_ASC,'DM_FECHA'=>SORT_ASC]);    

        return $dataProvider;
       
    }

       public function existencia($lote = null){ 
        $existencia = 0;

        if (isset($this->fecha_inicio) && !$this->fecha_inicio==''){
           $movs_anterior = $this->movimientos($lote);
          
           foreach ($movs_anterior as $key => $mov) {
             $existencia += $mov->codigo->DM_SIGNO*$mov->DM_CANT;
           }
        }
        return $existencia;
    }
    public function movimientos($lote)
    {
        $query = Movimientos_diarios::find();

        if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['<', 'DM_FECHA', $fecha_inicio_format]);
         }
     
        $query->andFilterWhere([
          'DM_FECVTO' => $lote,
        ]);


        $query->andFilterWhere(['like', 'DM_CODART', $this->articulo])
            ->andFilterWhere(['like', 'DM_DEPOSITO', $this->deposito]);

        $query->orderBy(['DM_FECVTO'=>SORT_ASC]);    

        return $query->all();
    }

    private function existe_articulo($codart,$deposito){

        if (($model = ArticGral::findOne(['AG_CODIGO' => $codart, 'AG_DEPOSITO' => $deposito])) !== null) {
            return true;
        } else {
            return false;
        }
    }
   
}

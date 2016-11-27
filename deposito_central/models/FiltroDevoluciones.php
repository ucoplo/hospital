<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `deposito_central\models\Remito_Adquisicion`.
 */
class FiltroDevoluciones extends FiltroReporte
{
    
    public $vales, $planillas, $sobrante;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','tipos','fecha_inicio','fecha_fin','planillas','sobrante'], 'safe'],
        ];
    }

    
   
    public function buscarGranel($sobrante)
    {
        $query = Devolucion_salas_renglones::find();

        $query->joinWith(['devolucion_encabezado']);
        $query->joinWith(['articulo']);

             
        if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

         if ($sobrante){
            $query->andFilterWhere(['DE_SOBRAN'=>1]);
         }else{
            $query->andFilterWhere(['DE_SOBRAN'=>0]);
         }
        

        $query->andFilterWhere(['like', 'PR_CODART', $this->articulo])
            ->andFilterWhere(['like', 'PR_DEPOSITO', $this->deposito]);
            
        $query->groupBy(['PR_CODART']);

        $query->select(['SUM(PR_CANTID) as cantidad',
                        'SUM(PR_CANTID*AG_PRECIO) as valor',
                        'PR_CODART as codigo','AG_NOMBRE as articulo']);

        $query->orderBy(['AG_NOMBRE'=>SORT_DESC]);    
       return $query;

    }
    
    public function buscar($params)
    {
        $this->load($params);
        $query = Devolucion_salas_renglones::find()->where("0=1");
        $query->groupBy(['PR_CODART']);

        $query->select(['SUM(PR_CANTID) as cantidad',
                        'SUM(PR_CANTID) as valor',
                        'PR_CODART as codigo','PR_CODART as articulo']);


       
      
        if ($this->planillas){
            $query1 = $this->buscarGranel(false);
            $query = (new \yii\db\Query())->groupBy(['codigo','articulo'])
              ->select(['SUM(cantidad) as cantidad',
                        'SUM(valor) as valor',
                        'codigo','articulo'])
              ->from(['salidas' => $query->union($query1)]);
             
        }
        if ($this->sobrante){
            $query1 = $this->buscarGranel(true);
            $query = (new \yii\db\Query())
              ->select(['SUM(cantidad) as cantidad',
                        'SUM(valor) as valor',
                        'codigo','articulo'])
              ->from(['salidas' => $query->union($query1)])
              ->groupBy(['codigo','articulo']);
        }
       

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;

    }

    public function getTipo_devolucion_descripcion(){
       
        if ($this->planillas && $this->sobrante)
            return "Planillas y Sobrantes de Sala";
        elseif($this->planillas)
           return "Planillas";
        elseif($this->sobrante)
            return "Sobrantes de Sala";
        else
            return '';
    }
}

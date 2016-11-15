<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class DevolucionesFiltro extends ReporteFiltro
{
    
    public $vales, $planillas, $sobrante;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','tipos','periodo_inicio','periodo_fin','vales','planillas','sobrante'], 'safe'],
        ];
    }

    
    public function buscarPaciente()
    {
        $query = Devolucion_salas_paciente_renglones::find();

        $query->joinWith(['devolucion_encabezado']);
        $query->joinWith(['monod']);

            
        if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }
        

        $query->andFilterWhere(['like', 'DV_CODMON', $this->monodroga])
            ->andFilterWhere(['like', 'DV_DEPOSITO', $this->deposito]);
            
        $query->groupBy(['DV_CODMON']);

        $query->select(['SUM(DV_CANTID) as cantidad',
                        'SUM(DV_CANTID*AG_PRECIO) as valor',
                        'DV_CODMON as codigo','AG_NOMBRE as monodroga']);

        $query->orderBy(['AG_NOMBRE'=>SORT_DESC]);    
       return $query;

    }


    public function buscarGranel($sobrante)
    {
        $query = Devolucion_salas_granel_renglones::find();

        $query->joinWith(['devolucion_encabezado']);
        $query->joinWith(['monod']);

             
        if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

         if ($sobrante){
            $query->andFilterWhere(['DE_SOBRAN'=>1]);
         }else{
            $query->andFilterWhere(['DE_SOBRAN'=>0]);
         }
        

        $query->andFilterWhere(['like', 'DF_CODMON', $this->monodroga])
            ->andFilterWhere(['like', 'DF_DEPOSITO', $this->deposito]);
            
        $query->groupBy(['DF_CODMON']);

        $query->select(['SUM(DF_CANTID) as cantidad',
                        'SUM(DF_CANTID*AG_PRECIO) as valor',
                        'DF_CODMON as codigo','AG_NOMBRE as monodroga']);

        $query->orderBy(['AG_NOMBRE'=>SORT_DESC]);    
       return $query;

    }
    
    public function buscar($params)
    {
        $this->load($params);
        $query = Devolucion_salas_granel_renglones::find()->where("0=1");
        $query->groupBy(['DF_CODMON']);

        $query->select(['SUM(DF_CANTID) as cantidad',
                        'SUM(DF_CANTID) as valor',
                        'DF_CODMON as codigo','DF_CODMON as monodroga']);


       
        if ($this->vales){
            $query1 = $this->buscarPaciente();
            $query = (new \yii\db\Query())
              ->select(['SUM(cantidad) as cantidad',
                        'SUM(valor) as valor',
                        'codigo','monodroga'])
              ->from(['salidas' => $query->union($query1)])
              ->groupBy(['codigo','monodroga']);
        }
        if ($this->planillas){
            $query1 = $this->buscarGranel(false);
            $query = (new \yii\db\Query())->groupBy(['codigo','monodroga'])
              ->select(['SUM(cantidad) as cantidad',
                        'SUM(valor) as valor',
                        'codigo','monodroga'])
              ->from(['salidas' => $query->union($query1)]);
             
        }
        if ($this->sobrante){
            $query1 = $this->buscarGranel(true);
            $query = (new \yii\db\Query())
              ->select(['SUM(cantidad) as cantidad',
                        'SUM(valor) as valor',
                        'codigo','monodroga'])
              ->from(['salidas' => $query->union($query1)])
              ->groupBy(['codigo','monodroga']);
        }
       

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;

    }
}

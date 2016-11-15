<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class AbcFiltro extends ReporteFiltro
{
    public $activos,$servicio;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','activos','periodo_inicio','periodo_fin','servicio','monodroga'], 'safe'],
        ];
    }

    public static function getListaServicios()
    {
        $opciones = Servicio::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'SE_CODIGO', 'SE_DESCRI');
    }


    
    public function abc($params)
    {
        $query = Movimientos_diarios::find();

        $query->joinWith(['monodroga']);
        $query->joinWith(['codigo']);
        
        

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
        // grid filtering conditions
         $query->andFilterWhere(['AG_ACTIVO'=>$this->activos,'MS_VALIDO'=>0]);

        $query->andFilterWhere(['like', 'MD_CODMON', $this->monodroga])
            ->andFilterWhere(['like', 'MD_DEPOSITO', $this->deposito])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query->groupBy(['MD_CODMON']);

        $query->select(['SUM(MD_CANT*MS_SIGNO) as consumo',
                        'SUM(MD_CANT*MS_SIGNO*AG_PRECIO) as consumo_valor',
                        '`mov_dia`.*']);

        $query->orderBy(['consumo_valor'=>SORT_DESC]);    
       return $dataProvider;

    }

    public function abc_servicio($params)
    {   
  
        $this->load($params);

        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $this->deposito,
             'PF_CODMON' => $this->monodroga,
        ]);
                

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'CM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'CM_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query1->groupBy(['PF_CODMON','CM_SERSOL']);

        $query1->select(["SUM(PF_CANTID) as cantidad","PF_CODMON as codmon",'CM_SERSOL as servicio']);


        $query2 = Consumo_medicamentos_pacientes_renglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['monodroga']);
        $query2->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $this->monodroga,
        ]);
                

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query2->andFilterWhere(['>=', 'CM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query2->andFilterWhere(['<=', 'CM_FECHA', $fecha_fin_format]);
         }

        $query2->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query2->groupBy(['VA_CODMON','CM_SERSOL']);

        $query2->select(["SUM(VA_CANTID) as cantidad","VA_CODMON as codmon",'CM_SERSOL as servicio']);

        $unionQuery = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","codmon",'servicio'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery->groupBy(['codmon','servicio']);
      
        

        $query1 = Devolucion_salas_granel_renglones::find();
        $query1->joinWith(['devolucion_encabezado']);
         $query1->joinWith(['monod']);
        $query1->andFilterWhere([
            'DF_DEPOSITO' => $this->deposito,
            'DF_CODMON' => $this->monodroga,
        ]);

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query1->groupBy(['DF_CODMON','DE_SERSOL']);

        $query1->select(["SUM(DF_CANTID) as cantidad","DF_CODMON as codmon",'DE_SERSOL as servicio']);


        $query2 = Devolucion_salas_paciente_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
        $query2->joinWith(['monod']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $this->monodroga,
        ]);
                

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query2->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query2->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query2->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query2->groupBy(['DV_CODMON','DE_SERSOL']);

        $query2->select(["SUM(DV_CANTID) as cantidad","DV_CODMON as codmon",'DE_SERSOL as servicio']);

        $unionQuery2 = (new \yii\db\Query())
          ->select(["sum(cantidad)*-1 as total_consumo","codmon",'servicio'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery2->groupBy(['codmon','servicio']);


        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(total_consumo) as total_consumo","sum(total_consumo*AG_PRECIO) as consumo_valor",
                    "codmon",'AG_NOMBRE','AG_PRECIO','servicio','se_descri'])
          ->from(['salidas' => $unionQuery->union($unionQuery2)]);

        $unionQuery3->groupBy(['codmon','servicio']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon ");  

        $unionQuery3->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = servicio");  

        $unionQuery3->orderBy(['se_descri'=>SORT_ASC,'consumo_valor'=>SORT_DESC]); 

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);
       
        return $dataProvider;
    }

}

<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use farmacia\models\Remito_adquisicion;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class ConsumosFiltro extends ReporteFiltro
{
   public $servicio,$servicios;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['monodroga'],'required', 'on' => 'salida_media'],
            [['servicio'],'required', 'on' => 'stock_sala'],
            [['deposito','periodo_inicio','periodo_fin'],'required', 'on' => 'indices'],
            [['deposito','clases','monodroga','periodo_inicio','periodo_fin','servicio'], 'safe'],
        ];
    }

    
    public function buscar_por_monodroga($params)
    {   

        $this->load($params);

        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $this->deposito,
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

        $query1->groupBy(['PF_CODMON']);

        $query1->select(["SUM(PF_CANTID) as cantidad","PF_CODMON as codmon"]);


        $query2 = Consumo_medicamentos_pacientes_renglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['monodroga']);
        $query2->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
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

        $query2->groupBy(['VA_CODMON']);

        $query2->select(["SUM(VA_CANTID) as cantidad","VA_CODMON as codmon"]);

        $unionQuery = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","codmon"])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery->groupBy(['codmon']);
      
        

        $query1 = Devolucion_salas_granel_renglones::find();
        $query1->joinWith(['devolucion_encabezado']);
         $query1->joinWith(['monod']);
        $query1->andFilterWhere([
            'DF_DEPOSITO' => $this->deposito,
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

        $query1->groupBy(['DF_CODMON']);

        $query1->select(["SUM(DF_CANTID) as cantidad","DF_CODMON as codmon"]);


        $query2 = Devolucion_salas_paciente_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
        $query2->joinWith(['monod']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
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

        $query2->groupBy(['DV_CODMON']);

        $query2->select(["SUM(DV_CANTID) as cantidad","DV_CODMON as codmon"]);

        $unionQuery2 = (new \yii\db\Query())
          ->select(["sum(cantidad)*-1 as total_consumo","codmon"])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery2->groupBy(['codmon']);


        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(total_consumo) as total_consumo","sum(total_consumo*AG_PRECIO) as valor_total","codmon",'AG_PRECIO','AG_NOMBRE'])
          ->from(['salidas' => $unionQuery->union($unionQuery2)]);

        $unionQuery3->groupBy(['codmon']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon");  

        $unionQuery3->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);   

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
            'key' => "codmon",
        ]);


        return $dataProvider;
    }
    public function buscar_por_monodroga_droga($codmon)
    {   

       
        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $this->deposito,
           'PF_CODMON' => $codmon
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

        $query1->select(['CM_FECHA as fecha',"PF_CANTID as cantidad","PF_CODMON as codmon",'SE_DESCRI as destinatario']);

        $query1->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = CM_SERSOL");  

        $query2 = Consumo_medicamentos_pacientes_renglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['monodroga']);
        $query2->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $codmon
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

       

        $query2->select(['CM_FECHA as fecha',"VA_CANTID as cantidad","VA_CODMON as codmon",'PA_APENOM as destinatario']);

        $query2->join('INNER JOIN', 'paciente',
                 "paciente.pa_hiscli = CM_HISCLI");  


        $unionQueryConsumo = (new \yii\db\Query())
          ->select(['fecha',"cantidad as total_consumo","codmon","destinatario"])
          ->from(['salidas' => $query1->union($query2,true)]);

        
        $query3 = Devolucion_salas_granel_renglones::find();
        $query3->joinWith(['devolucion_encabezado']);
         $query3->joinWith(['monod']);
        $query3->andFilterWhere([
            'DF_DEPOSITO' => $this->deposito,
            'DF_CODMON' => $codmon
        ]);

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query3->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query3->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query3->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query3->select(['DE_FECHA as fecha',"DF_CANTID as cantidad","DF_CODMON as codmon",'SE_DESCRI as destinatario']);

        $query3->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = DE_SERSOL");  

        $query4 = Devolucion_salas_paciente_renglones::find();
        $query4->joinWith(['devolucion_encabezado']);
        $query4->joinWith(['monod']);
        $query4->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $codmon
        ]);
                

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query4->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query4->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query4->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

       

        $query4->select(['DE_FECHA as fecha',"DV_CANTID as cantidad","DV_CODMON as codmon",'PA_APENOM as destinatario']);

        $query4->join('INNER JOIN', 'paciente',
                 "paciente.pa_hiscli = DE_HISCLI");  

        $unionQueryDevoluc = (new \yii\db\Query())
          ->select(['fecha',"(cantidad*-1) as total_consumo","codmon","destinatario"])
          ->from(['salidas' => $query3->union($query4,true)]);

      
        $unionQuery3 = (new \yii\db\Query())
          ->select(['fecha',"total_consumo","(total_consumo*AG_PRECIO) as valor_total","codmon",'AG_PRES','AG_NOMBRE','destinatario'])
          ->from(['salidas' => $unionQueryConsumo->union($unionQueryDevoluc,true)]);

        
        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon");  

        $unionQuery3->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);   
         $unionQuery3->orderBy(['fecha'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);

       
        return $dataProvider;
    }
    public function buscar_por_servicio($params)
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
          ->select(["sum(total_consumo) as total_consumo","codmon",'AG_PRECIO'
                    ,'AG_NOMBRE','servicio','se_descri','CONCAT(servicio,codmon) as `codigo`'])
          ->from(['salidas' => $unionQuery->union($unionQuery2)]);

        $unionQuery3->groupBy(['codmon','servicio']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon");  

        $unionQuery3->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);   

        $unionQuery3->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = servicio");  

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
            'key' => 'codigo',
        ]);
       
        return $dataProvider;
    }
    public function buscar_por_servicio_droga($codmon,$servicio)
    {   

     
        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $this->deposito,
             'PF_CODMON' => $codmon,
             'CM_SERSOL' => $servicio
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

       
        $query1->select(['CM_FECHA as fecha',"PF_CANTID as cantidad","PF_CODMON as codmon",'CM_SERSOL as servicio','SE_DESCRI as destinatario']);

        $query1->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = CM_SERSOL");  

        $query2 = Consumo_medicamentos_pacientes_renglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['monodroga']);
        $query2->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $codmon,
            'CM_SERSOL' => $servicio
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

       
        $query2->select(['CM_FECHA as fecha',"VA_CANTID as cantidad","VA_CODMON as codmon",'CM_SERSOL as servicio','PA_APENOM as destinatario']);

         $query2->join('INNER JOIN', 'paciente',
                 "paciente.pa_hiscli = CM_HISCLI");

        $unionQuery = (new \yii\db\Query())
          ->select(['fecha',"cantidad as total_consumo","codmon",'servicio','destinatario'])
          ->from(['salidas' => $query1->union($query2)]);

       
        $query1 = Devolucion_salas_granel_renglones::find();
        $query1->joinWith(['devolucion_encabezado']);
         $query1->joinWith(['monod']);
        $query1->andFilterWhere([
            'DF_DEPOSITO' => $this->deposito,
            'DF_CODMON' => $codmon,
            'DE_SERSOL' => $servicio,
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

        
        $query1->select(['DE_FECHA as fecha',"DF_CANTID as cantidad","DF_CODMON as codmon",'DE_SERSOL as servicio','SE_DESCRI as destinatario']);

        $query1->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = DE_SERSOL");  


        $query2 = Devolucion_salas_paciente_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
        $query2->joinWith(['monod']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $codmon,
            'DE_SERSOL' => $servicio,
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

        
        $query2->select(['DE_FECHA as fecha',"DV_CANTID as cantidad","DV_CODMON as codmon",'DE_SERSOL as servicio','PA_APENOM as destinatario']);

         $query2->join('INNER JOIN', 'paciente',
                 "paciente.pa_hiscli = DE_HISCLI");

        $unionQuery2 = (new \yii\db\Query())
          ->select(['fecha',"(cantidad*-1) as total_consumo","codmon",'servicio','destinatario'])
          ->from(['salidas' => $query1->union($query2,true)]);


        $unionQuery3 = (new \yii\db\Query())
          ->select(['fecha',"total_consumo","(total_consumo*AG_PRECIO) as valor_total","codmon",'AG_PRECIO'
                    ,'AG_NOMBRE','servicio','se_descri','destinatario','AG_PRES'])
          ->from(['salidas' => $unionQuery->union($unionQuery2,true)]);

        
        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon");  

        $unionQuery3->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);   
         $unionQuery3->orderBy(['fecha'=>SORT_ASC]);
        $unionQuery3->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = servicio");  

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
          
        ]);
       
       
        return $dataProvider;
    }

    public function buscar_por_servicio_clase($params)
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

        $query1->groupBy(['PF_CODMON','CM_SERSOL','AG_CODCLA']);

        $query1->select(["SUM(PF_CANTID) as cantidad","PF_CODMON as codmon",'CM_SERSOL as servicio','AG_CODCLA as clase']);


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

        $query2->groupBy(['VA_CODMON','CM_SERSOL','AG_CODCLA']);

        $query2->select(["SUM(VA_CANTID) as cantidad","VA_CODMON as codmon",'CM_SERSOL as servicio','AG_CODCLA as clase']);

        $unionQuery = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","codmon",'servicio','clase'])
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

        $query1->groupBy(['DF_CODMON','DE_SERSOL','AG_CODCLA']);

        $query1->select(["SUM(DF_CANTID) as cantidad","DF_CODMON as codmon",'DE_SERSOL as servicio','AG_CODCLA as clase']);


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

        $query2->groupBy(['DV_CODMON','DE_SERSOL','AG_CODCLA']);

        $query2->select(["SUM(DV_CANTID) as cantidad","DV_CODMON as codmon",'DE_SERSOL as servicio','AG_CODCLA as clase']);

        $unionQuery2 = (new \yii\db\Query())
          ->select(["sum(cantidad)*-1 as total_consumo","codmon",'servicio','clase'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery2->groupBy(['codmon','servicio','clase']);


        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(total_consumo) as total_consumo","codmon",'AG_PRECIO','AG_NOMBRE','servicio','se_descri','clase','CL_NOM'])
          ->from(['salidas' => $unionQuery->union($unionQuery2)]);

        $unionQuery3->groupBy(['codmon','servicio','clase']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon");  

        $unionQuery3->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);   

        $unionQuery3->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = servicio");  

        $unionQuery3->join('INNER JOIN', 'clases',
                 "clases.CL_COD = clase");  

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);
       
        return $dataProvider;
    }

    public function buscar_por_vales_planillas($params)
    {   

        $this->load($params);

        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $this->deposito,
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

        $query1->groupBy(['PF_CODMON']);

        $query1->select(["SUM(PF_CANTID) as cantidad","PF_CODMON as codmon"]);


        $query2 = Consumo_medicamentos_pacientes_renglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['monodroga']);
        $query2->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
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

        $query2->groupBy(['VA_CODMON']);

        $query2->select(["SUM(VA_CANTID) as cantidad","VA_CODMON as codmon"]);

        $unionQuery = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","codmon"])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery->groupBy(['codmon']);
      
        

        $query1 = Devolucion_salas_granel_renglones::find();
        $query1->joinWith(['devolucion_encabezado']);
         $query1->joinWith(['monod']);
        $query1->andFilterWhere([
            'DF_DEPOSITO' => $this->deposito,
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

        $query1->groupBy(['DF_CODMON']);

        $query1->select(["SUM(DF_CANTID) as cantidad","DF_CODMON as codmon"]);


        $query2 = Devolucion_salas_paciente_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
        $query2->joinWith(['monod']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
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

        $query2->groupBy(['DV_CODMON']);

        $query2->select(["SUM(DV_CANTID) as cantidad","DV_CODMON as codmon"]);

        $unionQuery2 = (new \yii\db\Query())
          ->select(["sum(cantidad)*-1 as total_consumo","codmon"])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery2->groupBy(['codmon']);


        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(total_consumo) as cantidad_entregada","'' as `cantidad_pedida`","codmon",'AG_NOMBRE as descripcion'])
          ->from(['salidas' => $unionQuery->union($unionQuery2)]);

        $unionQuery3->groupBy(['codmon']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon");  

        $unionQuery3->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);   

        $query2 = ValeEnfermeriaRenglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['monodroga']);
             
        $query2->andFilterWhere([
            'vale_enf.VE_DEPOSITO' => $this->deposito,
            'VE_CODMON' => $this->monodroga,
        ]);

        $query2->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);
        $query2->andFilterWhere(['IN', 'VE_SERSOL', $this->servicios]);

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query2->andFilterWhere(['>=', 'VE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query2->andFilterWhere(['<=', 'VE_FECHA', $fecha_fin_format]);
         }

        $query2->select(['sum(VE_CANTID) as total_pedido','AG_NOMBRE','VE_CODMON as codmon']);

        $query2->groupBy(['VE_CODMON']);

        $query_granel = ValeGranelRenglones::find();
        $query_granel->joinWith(['vale']);
        $query_granel->joinWith(['monodroga']);
             
        $query_granel->andFilterWhere([
            'vale_mon.VM_DEPOSITO' => $this->deposito,
            'VM_CODMON' => $this->monodroga,
        ]);

        $query_granel->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);
        $query_granel->andFilterWhere(['IN', 'VM_SERSOL', $this->servicios]);

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query_granel->andFilterWhere(['>=', 'VM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query_granel->andFilterWhere(['<=', 'VM_FECHA', $fecha_fin_format]);
         }

        $query_granel->select(['sum(VM_CANTID) as total_pedido','AG_NOMBRE','VM_CODMON as codmon']);

        $query_granel->groupBy(['VM_CODMON']);

        $union_pedidos = (new \yii\db\Query())
          ->select(["'' as `cantidad_entregada`","sum(total_pedido) as `cantidad_pedida`","codmon",'AG_NOMBRE as descripcion'])
          ->from(['salidas' => $query2->union($query_granel)]);

        $union_pedidos->groupBy(['codmon']);

        $query_final = (new \yii\db\Query())
          ->select(["sum(cantidad_entregada) as cantidad_entregada","sum(cantidad_pedida) as `cantidad_pedida`","codmon",'descripcion'])
          ->from(['salidas' => $unionQuery3->union($union_pedidos)]);

          $query_final->groupBy(['codmon']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query_final,
        ]);
       
        return $dataProvider;
    }

    public function obtener_query_stock_sala($params)
    {
         $this->load($params);

        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['monodroga']);
      
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $this->deposito,
            'PF_CODMON' => $this->monodroga,
            'AG_FRACSAL' => 'S',
            'CM_SERSOL' => $this->servicio,
        ]);
                
        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query1->select(["CM_FECHA","CM_HORA",
                        'PF_CANTID','AG_NOMBRE','PF_CODMON as codmon']);

        $query = (new \yii\db\Query())
          ->select(["AG_NOMBRE as nombre","CM_FECHA as fecha","CM_HORA as hora",
                    "'' as `cantidad_entregada`",'PF_CANTID as cantidad_recibida',
                    "'Entrega Granel' as `destinatario`",'codmon'])
          ->from(['consumos' => $query1]);

        $query2 = ValeEnfermeriaRenglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['monodroga']);
        $query2->joinWith(['paciente']);
      
        $query2->andFilterWhere([
            'vale_enf.VE_DEPOSITO' => $this->deposito,
            'VE_CODMON' => $this->monodroga,
            'AG_FRACSAL' => 'S',
            'VE_SERSOL' => $this->servicio,
        ]);

        $query2->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query2->select(["VE_FECHA","VE_HORA",
                        'VE_CANTID','AG_NOMBRE','VE_IDINTERNA','VE_CODMON as codmon','PA_APENOM']);

        $query3 = (new \yii\db\Query())
          ->select(["AG_NOMBRE as nombre","VE_FECHA as fecha","VE_HORA as hora"
                        ,'VE_CANTID as cantidad_entregada',
                        "'' as `cantidad_recibida`","PA_APENOM as `destinatario`",'codmon'])
          ->from(['consumos' => $query2]);

        $unionQuery = (new \yii\db\Query())

              ->from(['salidas' => $query->union($query3)]);

        $unionQuery->orderBy(['nombre'=>SORT_ASC,'fecha'=>SORT_ASC]);

        //Devoluciones
        $query_devolG = Devolucion_salas_granel_renglones::find();
        $query_devolG->joinWith(['devolucion_encabezado']);
        $query_devolG->joinWith(['monod']);
      
        $query_devolG->andFilterWhere([
            'DF_DEPOSITO' => $this->deposito,
            'DF_CODMON' => $this->monodroga,
            'AG_FRACSAL' => 'S',
            'DE_SERSOL' => $this->servicio,
        ]);
         
        $query_devolG->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query_devolG->select(["DE_FECHA","DE_HORA",
                        'DF_CANTID','AG_NOMBRE','DF_CODMON as codmon']);

        $query_devolG = (new \yii\db\Query())
          ->select(["AG_NOMBRE as nombre","DE_FECHA as fecha","DE_HORA as hora"
                        ,'DF_CANTID as cantidad_entregada',
                        "'' as `cantidad_recibida`","'Devolución Granel' as `destinatario`",'codmon'])
          ->from(['devolg' => $query_devolG]);


        $query_devolP = Devolucion_salas_paciente_renglones::find();
        $query_devolP->joinWith(['devolucion_encabezado']);
        $query_devolP->joinWith(['monod']);
        $query_devolP->joinWith(['paciente']);
      
        $query_devolP->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $this->monodroga,
            'AG_FRACSAL' => 'S',
            'DE_SERSOL' => $this->servicio,
        ]);
       
        $query_devolP->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query_devolP->select(["DE_FECHA","DE_HORA",
                        'DV_CANTID','AG_NOMBRE',"DV_HISCLI",'DV_CODMON as codmon','PA_APENOM']);

        $query_devolP = (new \yii\db\Query())
          ->select(["AG_NOMBRE as nombre","DE_FECHA as fecha","DE_HORA as hora"
                        ,'DV_CANTID as cantidad_entregada',
                        "'' as `cantidad_recibida`","PA_APENOM as `destinatario`",'codmon'])
          ->from(['consumos' => $query_devolP]);

        $unionquery_devol = (new \yii\db\Query())
              ->from(['devolucion' => $query_devolG->union($query_devolP)]);
        $unionquery_devol->orderBy(['nombre'=>SORT_ASC,'fecha'=>SORT_ASC]);

        
        $unionqueryfinal = (new \yii\db\Query())
              ->from(['final' => $unionQuery->union($unionquery_devol)]);
        $unionqueryfinal->orderBy(['nombre'=>SORT_ASC,'fecha'=>SORT_ASC]);

        return $unionqueryfinal;
    }

    public function buscar_stock_monodrogas_sala($params)
    {
        $query = $this->obtener_query_stock_sala($params);

        if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'fecha', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'fecha', $fecha_fin_format]);
         }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;

    }

    public function existencia_sala($params,$codmon)
    {
        $query = $this->obtener_query_stock_sala($params);

        if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['<', 'fecha', $fecha_inicio_format]);
         }

        $query->andFilterWhere([
            'codmon' => $codmon,
        ]);

        $query_negativos = (new \yii\db\Query())
          ->select(['sum(cantidad_entregada*-1) as total'])
          ->from(['consumos' => $query]);

         $query_positivos = (new \yii\db\Query())
          ->select(['sum(cantidad_recibida) as total'])
          ->from(['consumos' => $query]);
       
         $existencia = (new \yii\db\Query())
              ->from(['salidas' => $query_negativos->union($query_positivos)])->sum('total');
        
        
        return $existencia;

    }

     public function buscar_techos($params)
    {
         $this->load($params);

         $query = Techo::find();
         $query->joinWith(['monodroga']);
   
         $query->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query->andFilterWhere(['like', 'TM_DEPOSITO', $this->deposito]);
          
        $query->select(["AG_CODIGO","AG_NOMBRE",
                        "TM_CANTID"]);

         $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
       
        return $dataProvider;

    }

     public function buscar_salida_media($params)
    {   

        $this->load($params);

        $fecha_inicio = date('Y-m-d', strtotime('-1 year'));

        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $this->deposito,
            'PF_CODMON' => $this->monodroga,
        ]);
        
        $query1->andFilterWhere(['>=','CM_FECHA', $fecha_inicio]);        

        $query1->groupBy(['CM_FECHA']);

        $query1->select(["SUM(PF_CANTID) as cantidad",'CM_FECHA as fecha']);


        $query2 = Consumo_medicamentos_pacientes_renglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['monodroga']);
        $query2->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $this->monodroga, 
        ]);
                
        $query2->andFilterWhere(['>=','CM_FECHA', $fecha_inicio]);        
         
        $query2->groupBy(['CM_FECHA']);

        $query2->select(["SUM(VA_CANTID) as cantidad",'CM_FECHA as fecha']);

        $unionQuery = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo",'fecha'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery->groupBy(['fecha']);
      
        

        $query1 = Devolucion_salas_granel_renglones::find();
        $query1->joinWith(['devolucion_encabezado']);
         $query1->joinWith(['monod']);
        $query1->andFilterWhere([
            'DF_DEPOSITO' => $this->deposito,
            'DF_CODMON' => $this->monodroga, 
        ]);

        $query1->andFilterWhere(['>=','DE_FECHA', $fecha_inicio]);         
        
        $query1->groupBy(['DE_FECHA']);

        $query1->select(["SUM(DF_CANTID) as cantidad","DE_FECHA as fecha"]);


        $query2 = Devolucion_salas_paciente_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
        $query2->joinWith(['monod']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $this->monodroga, 
        ]);
                

        $query2->andFilterWhere(['>=','DE_FECHA', $fecha_inicio]);         

        $query2->groupBy(['DE_FECHA']);

        $query2->select(["SUM(DV_CANTID) as cantidad","DE_FECHA as fecha"]);

        $unionQuery2 = (new \yii\db\Query())
          ->select(["sum(cantidad)*-1 as total_consumo",'fecha'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery2->groupBy(['fecha']);


        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(total_consumo) as total_consumo",'fecha'])
          ->from(['salidas' => $unionQuery->union($unionQuery2)]);

        $unionQuery3->groupBy(['fecha']);
       
        $unionQuery3->orderBy(['fecha'=>SORT_ASC]); 

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);


        return $unionQuery3->all();
    }

    private function consumo_query(){
       
        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $this->deposito,
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

        $query1->groupBy(['PF_CODMON']);

        $query1->select(["SUM(PF_CANTID) as cantidad","PF_CODMON as codmon"]);


        $query2 = Consumo_medicamentos_pacientes_renglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['monodroga']);
        $query2->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
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

        $query2->groupBy(['VA_CODMON']);

        $query2->select(["SUM(VA_CANTID) as cantidad","VA_CODMON as codmon"]);

        $unionQuery = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","codmon"])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery->groupBy(['codmon']);
      
        

        $query1 = Devolucion_salas_granel_renglones::find();
        $query1->joinWith(['devolucion_encabezado']);
         $query1->joinWith(['monod']);
        $query1->andFilterWhere([
            'DF_DEPOSITO' => $this->deposito,
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

        $query1->groupBy(['DF_CODMON']);

        $query1->select(["SUM(DF_CANTID) as cantidad","DF_CODMON as codmon"]);


        $query2 = Devolucion_salas_paciente_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
        $query2->joinWith(['monod']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
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

        $query2->groupBy(['DV_CODMON']);

        $query2->select(["SUM(DV_CANTID) as cantidad","DV_CODMON as codmon"]);

        $unionQuery2 = (new \yii\db\Query())
          ->select(["sum(cantidad)*-1 as total_consumo","codmon"])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery2->groupBy(['codmon']);


        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(total_consumo) as total_consumo","codmon",'AG_PRECIO','AG_NOMBRE'])
          ->from(['salidas' => $unionQuery->union($unionQuery2)]);

        $unionQuery3->groupBy(['codmon']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon");

        $unionQuery3->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);        

        return $unionQuery3;

    }
    public function buscar_indices($params)
    {   

        $this->load($params);

        $query = $this->consumo_query();
      

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    function existencia($monodroga,$deposito,$fecha){
        
        $existencia = 0;    
        $query = Remito_adquisicion_renglones::find();

        // add conditions that should always apply here
        $query->joinWith(['remito']);
        $query->joinWith(['monodroga']);

        $query->andFilterWhere(['<=', 'RE_FECHA', $fecha]);
 
        $query->andFilterWhere(['like', 'RM_CODMON', $monodroga])
            ->andFilterWhere(['like', 'RM_DEPOSITO', $deposito]);
        
        $existencia += $query->sum('RM_CANTID');

        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $deposito,
            'PF_CODMON' => $monodroga,
        ]);
                
        $query1->andFilterWhere(['<=', 'CM_FECHA', $fecha]);
         
        $existencia -= $query1->sum('PF_CANTID'); 

        $query2 = Consumo_medicamentos_pacientes_renglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['monodroga']);
        $query2->andFilterWhere([
            'VA_DEPOSITO' => $deposito,
            'VA_CODMON' => $monodroga,
        ]);

        $query2->andFilterWhere(['<=', 'CM_FECHA', $fecha]);

        $existencia -= $query2->sum('VA_CANTID'); 

        $query2 = Devolucion_salas_paciente_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
        $query2->joinWith(['monod']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $deposito,
            'DV_CODMON' => $monodroga,
        ]);

        $query2->andFilterWhere(['<=', 'DE_FECHA', $fecha]);

        $existencia += $query2->sum('DV_CANTID'); 

        $query1 = Devolucion_salas_granel_renglones::find();
        $query1->joinWith(['devolucion_encabezado']);
        $query1->joinWith(['monod']);
        $query1->andFilterWhere([
            'DF_DEPOSITO' => $deposito,
            'DF_CODMON' => $monodroga,
        ]);

        $query1->andFilterWhere(['<=', 'DE_FECHA', $fecha]);

        $existencia += $query1->sum('DF_CANTID'); 

        return (($existencia>0)?$existencia:0);

    }
    function diferenciaDias($inicio, $fin)
    {
        $inicio = strtotime($inicio);
        $fin = strtotime($fin);
        $dif = $fin - $inicio;
        $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
        return ceil($diasFalt);
    }
    function existencia_promedio($codmon){

        $existencia_suma = 0;
        $fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
        $fecha_inicio = $fecha_inicio->format('Y-m-d');
        $fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
        $fecha_fin = $fecha_fin->format('Y-m-d');
        $fecha = $fecha_inicio;
        while ($fecha<=$fecha_fin){
            $existencia_suma += $this->existencia($codmon,$this->deposito,$fecha);

            $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
            $fecha = date ( 'Y-m-d' , $nuevafecha );
        }

        $dias = $this->diferenciaDias($fecha_inicio,$fecha_fin);

        return $existencia_suma/$dias;

    }

    function buscar_alarmas_estaticas($params){

        $this->load($params);
        
        $this->periodo_inicio =  Date('d-m-Y', strtotime('-7 day'));

        $this->periodo_fin = Date('d-m-Y');

        $query = $this->consumo_query();
      
        $query->join('INNER JOIN', 'alarmas',
                 "alarmas.AL_CODMON = codmon and total_consumo<alarmas.AL_MIN or total_consumo>alarmas.AL_MAX");

        $query->andFilterWhere([
            'AL_DEPOSITO' => $this->deposito,
        ]);        
        $query->select(["total_consumo","codmon",'AG_PRECIO','AG_NOMBRE','AL_MIN','AL_MAX']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;

    }


    private function consumo_alarma_query(){
        
        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $this->deposito,
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

        $query1->groupBy(['PF_CODMON','CM_FECHA']);

        $query1->select(["SUM(PF_CANTID) as cantidad","PF_CODMON as codmon","CM_FECHA as fecha"]);


        $query2 = Consumo_medicamentos_pacientes_renglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['monodroga']);
        $query2->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
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

        $query2->groupBy(['VA_CODMON','CM_FECHA']);

        $query2->select(["SUM(VA_CANTID) as cantidad","VA_CODMON as codmon","CM_FECHA as fecha"]);

        $unionQuery = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","codmon","fecha"])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery->groupBy(['fecha','codmon']);
      
        

        $query1 = Devolucion_salas_granel_renglones::find();
        $query1->joinWith(['devolucion_encabezado']);
         $query1->joinWith(['monod']);
        $query1->andFilterWhere([
            'DF_DEPOSITO' => $this->deposito,
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

        $query1->groupBy(['DF_CODMON','DE_FECHA']);

        $query1->select(["SUM(DF_CANTID) as cantidad","DF_CODMON as codmon","DE_FECHA as fecha"]);


        $query2 = Devolucion_salas_paciente_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
        $query2->joinWith(['monod']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
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

        $query2->groupBy(['DV_CODMON','DE_FECHA']);

        $query2->select(["SUM(DV_CANTID) as cantidad","DV_CODMON as codmon","DE_FECHA as fecha"]);

        $unionQuery2 = (new \yii\db\Query())
          ->select(["sum(cantidad)*-1 as total_consumo","codmon","fecha"])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery2->groupBy(['fecha','codmon']);


        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(total_consumo) as total_consumo","codmon",'fecha'])
          ->from(['salidas' => $unionQuery->union($unionQuery2)]);

        $unionQuery3->groupBy(['fecha','codmon']);
        //echo "<pre>";print_r($unionQuery3->all());echo "</pre>";die();

        $unionQuery3->andFilterWhere(['>', 'total_consumo', 0]);

        $unionQuery4 = (new \yii\db\Query())
          ->select(['AVG(total_consumo)+(2*STD(total_consumo)) as AL_MAX',
                              'AVG(total_consumo)-(2*STD(total_consumo)) as AL_MIN',
                              'codmon as codmon_al'])
          ->from(['alarmas' => $unionQuery3]);
        $unionQuery4->groupBy(['codmon']);
         
     
       return $unionQuery4;  
    }


    function buscar_alarmas_dinamicas($params){

        
        //Las alarmas de cada Monodroga se calculan en base al consumo en el último año.
        $this->periodo_inicio =  Date('d-m-Y', strtotime('-365 day'));

        $this->periodo_fin = Date('d-m-Y');

        $this->load($params);
        //Obtenego los limites superiores e inferiores de alarmas
        $query_desvios = $this->consumo_alarma_query();

        $this->periodo_inicio =  Date('d-m-Y', strtotime('-90 day'));

        $this->load($params);

        //Obtenego los consumos de los ultimos 90 días
        $query_consumo_puntual = $this->consumo_query();
        
        $query_consumo_puntual->innerJoin(['d' => $query_desvios], 'd.codmon_al = codmon and total_consumo<d.AL_MIN or total_consumo>d.AL_MAX');
         
        $query_consumo_puntual->select(["total_consumo","codmon",'AG_PRECIO','AG_NOMBRE','AL_MIN','AL_MAX']);
       
        $dataProvider = new ActiveDataProvider([
            'query' => $query_consumo_puntual,
        ]);

        return $dataProvider;

    }

}

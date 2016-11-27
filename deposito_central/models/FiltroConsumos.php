<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Remito_adquisicion;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `deposito_central\models\Remito_Adquisicion`.
 */
class FiltroConsumos extends FiltroReporte
{
   public $servicio,$servicios;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['articulo'],'required', 'on' => 'salida_media'],
            [['servicio'],'required', 'on' => 'stock_sala'],
            [['deposito','fecha_inicio','fecha_fin'],'required', 'on' => 'indices'],
            [['deposito','clases','articulo','fecha_inicio','fecha_fin','servicio'], 'safe'],
        ];
    }

    
    public function buscar_por_articulo($params)
    {   

        $this->load($params);

        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['articulo']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
        ]);
                

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'PE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'PE_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query1->groupBy(['PR_CODART']);

        $query1->select(["SUM(PR_CANTID) as cantidad","PR_CODART as codart"]);

        $query2 = Devolucion_salas_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
         $query2->joinWith(['articulo']);
        $query2->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
        ]);

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query2->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query2->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query2->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query2->groupBy(['PR_CODART']);

        $query2->select(["SUM(PR_CANTID)*-1 as cantidad","PR_CODART as codart"]);
      
        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","sum(cantidad*AG_PRECIO) as valor_total","codart",'AG_PRECIO','AG_NOMBRE'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery3->groupBy(['codart']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codart");  

        $unionQuery3->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);   

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
            'key' => "codart",
        ]);


        return $dataProvider;
    }
    public function buscar_por_articulo_detalle($codart)
    {   
        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['articulo']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
           'PR_CODART' => $codart
        ]);
                

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'PE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'PE_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query1->select(['PE_FECHA as fecha',"PR_CANTID as cantidad","PR_CODART as codart",'SE_DESCRI as destinatario']);

        $query1->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = PE_SERSOL");  

              
        $query3 = Devolucion_salas_renglones::find();
        $query3->joinWith(['devolucion_encabezado']);
         $query3->joinWith(['articulo']);
        $query3->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $codart
        ]);

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query3->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query3->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query3->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query3->select(['DE_FECHA as fecha',"(PR_CANTID*-1) as cantidad","PR_CODART as codart",'SE_DESCRI as destinatario']);

        $query3->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = DE_SERSOL");  

      
      
        $unionQuery3 = (new \yii\db\Query())
          ->select(['fecha',"cantidad as total_consumo","(cantidad*AG_PRECIO) as valor_total","codart",'AG_PRES','AG_NOMBRE','destinatario'])
          ->from(['salidas' => $query1->union($query3,true)]);

        
        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codart");  

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

        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['articulo']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
             'PR_CODART' => $this->articulo,
        ]);
                

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'PE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'PE_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query1->groupBy(['PR_CODART','PE_SERSOL']);

        $query1->select(["SUM(PR_CANTID) as cantidad","PR_CODART as codart",'PE_SERSOL as servicio']);


        $query2 = Devolucion_salas_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
         $query2->joinWith(['articulo']);
        $query2->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $this->articulo,
        ]);

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query2->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query2->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query2->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query2->groupBy(['PR_CODART','DE_SERSOL']);

        $query2->select(["(SUM(PR_CANTID)*-1) as cantidad","PR_CODART as codart",'DE_SERSOL as servicio']);

        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","codart",'AG_PRECIO'
                    ,'AG_NOMBRE','servicio','se_descri','CONCAT(servicio,codart) as `codigo`'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery3->groupBy(['codart','servicio']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codart");  

        $unionQuery3->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);   

        $unionQuery3->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = servicio");  

        $unionQuery3->orderBy(['servicio'=>SORT_ASC,'codart'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
            'key' => 'codigo',
        ]);
       
        return $dataProvider;
    }
    public function buscar_por_servicio_detalle($codart,$servicio)
    {   

     
        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['articulo']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
             'PR_CODART' => $codart,
             'PE_SERSOL' => $servicio
        ]);
                

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'PE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'PE_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

       
        $query1->select(['PE_FECHA as fecha',"PR_CANTID as cantidad","PR_CODART as codart",'PE_SERSOL as servicio','SE_DESCRI as destinatario']);

        $query1->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = PE_SERSOL");  

              
        $query2 = Devolucion_salas_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
         $query2->joinWith(['articulo']);
        $query2->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $codart,
            'DE_SERSOL' => $servicio,
        ]);

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query2->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query2->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query2->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        
        $query2->select(['DE_FECHA as fecha',"(PR_CANTID*-1) as cantidad","PR_CODART as codart",'DE_SERSOL as servicio','SE_DESCRI as destinatario']);

        $query2->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = DE_SERSOL");  


       
        $unionQuery3 = (new \yii\db\Query())
          ->select(['fecha',"cantidad as total_consumo","(cantidad*AG_PRECIO) as valor_total","codart",'AG_PRECIO'
                    ,'AG_NOMBRE','servicio','se_descri','destinatario','AG_PRES'])
          ->from(['salidas' => $query1->union($query2,true)]);

        
        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codart");  

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

        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['articulo']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
             'PR_CODART' => $this->articulo,
        ]);
                

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'PE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'PE_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query1->groupBy(['PR_CODART','PE_SERSOL','AG_CODCLA']);

        $query1->select(["SUM(PR_CANTID) as cantidad","PR_CODART as codart",'PE_SERSOL as servicio','AG_CODCLA as clase']);

        $query2 = Devolucion_salas_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
         $query2->joinWith(['articulo']);
        $query2->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODMON' => $this->articulo,
        ]);

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query2->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query2->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query2->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query2->groupBy(['PR_CODART','DE_SERSOL','AG_CODCLA']);

        $query2->select(["(SUM(PR_CANTID)*-1) as cantidad","PR_CODART as codart",'DE_SERSOL as servicio','AG_CODCLA as clase']);
    
        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","codart",'AG_PRECIO','AG_NOMBRE','servicio','se_descri','clase','CL_NOM'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery3->groupBy(['codart','servicio','clase']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codart");  

        $unionQuery3->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);   

        $unionQuery3->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = servicio");  

        $unionQuery3->join('INNER JOIN', 'clases',
                 "clases.CL_COD = clase");  

        $unionQuery3->orderBy(['se_descri'=>SORT_ASC,'CL_NOM'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);
       
        return $dataProvider;
    }

     public function buscar_salida_media($params)
    {   

        $this->load($params);

        $fecha_inicio = date('Y-m-d', strtotime('-1 year'));

        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['articulo']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $this->articulo,
        ]);
        
        $query1->andFilterWhere(['>=','PE_FECHA', $fecha_inicio]);        

        $query1->groupBy(['PE_FECHA']);

        $query1->select(["SUM(PR_CANTID) as cantidad",'PE_FECHA as fecha']);

        $query2 = Devolucion_salas_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
         $query2->joinWith(['articulo']);
        $query2->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $this->articulo, 
        ]);

        $query2->andFilterWhere(['>=','DE_FECHA', $fecha_inicio]);         
        
        $query2->groupBy(['DE_FECHA']);

        $query2->select(["SUM(PR_CANTID)*-1 as cantidad","DE_FECHA as fecha"]);


        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo",'fecha'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery3->groupBy(['fecha']);
       
        $unionQuery3->orderBy(['fecha'=>SORT_ASC]); 

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);


        return $unionQuery3->all();
    }

    private function consumo_query(){
       
        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['articulo']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
        ]);
                

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'PE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'PE_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query1->groupBy(['PR_CODART']);

        $query1->select(["SUM(PR_CANTID) as cantidad","PR_CODART as codart"]);

        $query2 = Devolucion_salas_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
         $query2->joinWith(['articulo']);
        $query2->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
        ]);

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query2->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query2->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query2->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query2->groupBy(['PR_CODART']);

        $query2->select(["SUM(PR_CANTID)*-1 as cantidad","PR_CODART as codart"]);


      
        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","codart",'AG_PRECIO','AG_NOMBRE'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery3->groupBy(['codart']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codart");

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

    function existencia($articulo,$deposito,$fecha){
        
        $existencia = 0;    
        $query = Remito_adquisicion_renglones::find();

        // add conditions that should always apply here
        $query->joinWith(['remito']);
        $query->joinWith(['articulo']);

        $query->andFilterWhere(['<=', 'RA_FECHA', $fecha]);
 
        $query->andFilterWhere(['like', 'AR_CODART', $articulo])
            ->andFilterWhere(['like', 'AR_DEPOSITO', $deposito]);
        
        $existencia += $query->sum('AR_CANTID');

        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['articulo']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $deposito,
            'PR_CODART' => $articulo,
        ]);
                
        $query1->andFilterWhere(['<=', 'PE_FECHA', $fecha]);
         
        $existencia -= $query1->sum('PR_CANTID'); 

        $query1 = Devolucion_salas_renglones::find();
        $query1->joinWith(['devolucion_encabezado']);
        $query1->joinWith(['articulo']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $deposito,
            'PR_CODART' => $articulo,
        ]);

        $query1->andFilterWhere(['<=', 'DE_FECHA', $fecha]);

        $existencia += $query1->sum('PR_CANTID'); 

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
    function existencia_promedio($codart){

        $existencia_suma = 0;
        $fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
        $fecha_inicio = $fecha_inicio->format('Y-m-d');
        $fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
        $fecha_fin = $fecha_fin->format('Y-m-d');
        $fecha = $fecha_inicio;
        while ($fecha<=$fecha_fin){
            $existencia_suma += $this->existencia($codart,$this->deposito,$fecha);

            $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
            $fecha = date ( 'Y-m-d' , $nuevafecha );
        }

        $dias = $this->diferenciaDias($fecha_inicio,$fecha_fin);

        return $existencia_suma/$dias;

    }

    function buscar_alarmas_estaticas($params){

        $this->load($params);
        
        $this->fecha_inicio =  Date('d-m-Y', strtotime('-7 day'));

        $this->fecha_fin = Date('d-m-Y');

        $query = $this->consumo_query();
        
        $query2 = (new \yii\db\Query())
          ->select(["total_consumo","codart",'AG_PRECIO','AG_NOMBRE','AL_MIN','AL_MAX'])
          ->from(['salidas' => $query]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query2,
        ]);

        $query2->join('INNER JOIN', 'alarmas',
                 "alarmas.AL_CODMON = codart");

       
        $query2->andFilterWhere(['<', 'total_consumo', 'AL_MIN'])
                ->andFilterWhere(['>', 'total_consumo', 'AL_MAX']);

        $query2->andFilterWhere([
            'AL_DEPOSITO' => $this->deposito,
        ]);        

        return $dataProvider;

    }


    private function consumo_alarma_query(){
        
        $query1 = Consumo_medicamentos_granel_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['articulo']);
        $query1->andFilterWhere([
            'PF_DEPOSITO' => $this->deposito,
        ]);
                

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'CM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'CM_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query1->groupBy(['PF_CODMON','CM_FECHA']);

        $query1->select(["SUM(PF_CANTID) as cantidad","PF_CODMON as codart","CM_FECHA as fecha"]);


        $query2 = Consumo_medicamentos_pacientes_renglones::find();
        $query2->joinWith(['vale']);
        $query2->joinWith(['articulo']);
        $query2->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
        ]);
                

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query2->andFilterWhere(['>=', 'CM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query2->andFilterWhere(['<=', 'CM_FECHA', $fecha_fin_format]);
         }

        $query2->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query2->groupBy(['VA_CODMON','CM_FECHA']);

        $query2->select(["SUM(VA_CANTID) as cantidad","VA_CODMON as codart","CM_FECHA as fecha"]);

        $unionQuery = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","codart","fecha"])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery->groupBy(['fecha','codart']);
      
        

        $query1 = Devolucion_salas_granel_renglones::find();
        $query1->joinWith(['devolucion_encabezado']);
         $query1->joinWith(['monod']);
        $query1->andFilterWhere([
            'DF_DEPOSITO' => $this->deposito,
        ]);

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query1->groupBy(['DF_CODMON','DE_FECHA']);

        $query1->select(["SUM(DF_CANTID) as cantidad","DF_CODMON as codart","DE_FECHA as fecha"]);


        $query2 = Devolucion_salas_paciente_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
        $query2->joinWith(['monod']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
        ]);
                

         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query2->andFilterWhere(['>=', 'DE_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query2->andFilterWhere(['<=', 'DE_FECHA', $fecha_fin_format]);
         }

        $query2->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query2->groupBy(['DV_CODMON','DE_FECHA']);

        $query2->select(["SUM(DV_CANTID) as cantidad","DV_CODMON as codart","DE_FECHA as fecha"]);

        $unionQuery2 = (new \yii\db\Query())
          ->select(["sum(cantidad)*-1 as total_consumo","codart","fecha"])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery2->groupBy(['fecha','codart']);


        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(total_consumo) as total_consumo","codart",'fecha'])
          ->from(['salidas' => $unionQuery->union($unionQuery2)]);

        $unionQuery3->groupBy(['fecha','codart']);
        //echo "<pre>";print_r($unionQuery3->all());echo "</pre>";die();

        $unionQuery3->andFilterWhere(['>', 'total_consumo', 0]);

        $unionQuery4 = (new \yii\db\Query())
          ->select(['AVG(total_consumo)+(2*STD(total_consumo)) as AL_MAX',
                              'AVG(total_consumo)-(2*STD(total_consumo)) as AL_MIN',
                              'codart as codart_al'])
          ->from(['alarmas' => $unionQuery3]);
        $unionQuery4->groupBy(['codart']);
         
     
       return $unionQuery4;  
    }


    function buscar_alarmas_dinamicas($params){

        
        //Las alarmas de cada Monodroga se calculan en base al consumo en el último año.
        $this->fecha_inicio =  Date('d-m-Y', strtotime('-365 day'));

        $this->fecha_fin = Date('d-m-Y');

        $this->load($params);
        //Obtenego los limites superiores e inferiores de alarmas
        $query_desvios = $this->consumo_alarma_query();

        $this->fecha_inicio =  Date('d-m-Y', strtotime('-90 day'));

        $this->load($params);

        //Obtenego los consumos de los ultimos 90 días
        $query_consumo_puntual = $this->consumo_query();
        
        $query_consumo_puntual->innerJoin(['d' => $query_desvios], 'd.codart_al = codart and total_consumo<d.AL_MIN or total_consumo>d.AL_MAX');
         
        $query_consumo_puntual->select(["total_consumo","codart",'AG_PRECIO','AG_NOMBRE','AL_MIN','AL_MAX']);
       
        $dataProvider = new ActiveDataProvider([
            'query' => $query_consumo_puntual,
        ]);

        return $dataProvider;

    }

}

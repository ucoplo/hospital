<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class ConsumosPacienteFiltro extends ReporteFiltro
{
   public $unidad_diag,$droga,$hiscli,$servicio,$accion_terapeutica,$medsol,$programa,$obrasocial,$unidad_sanitaria;

    public function rules()
    {
        return [
            [['deposito','hiscli'], 'required'],
            [['deposito','clases','monodroga','periodo_inicio','periodo_fin','unidad_diag','droga','hiscli','servicio','accion_terapeutica','programa','obrasocial','unidad_sanitaria'], 'safe'],
        ];
    }


     public function attributeLabels()
    {
        
        $labels= [
            'hiscli' => 'Historia Clínica',
            'accion_terapeutica' => 'Acción Terapeutica',
            'unidad_diag' => 'Unidad de Diagnóstico',
            'medsol' => 'Médico Solicitante',
            'programa' => 'Programa',
            'obrasocial' => 'Obra Social',
            'unidad_sanitaria' =>'Unidad Sanitaria',
        ];

        return array_merge(parent::attributeLabels(),$labels);
    }

    public static function getListaDrogas()
    {
        $opciones = Droga::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'DR_CODIGO', 'DR_DESCRI');
    }

    public static function getListaUnidDiagno()
    {
        $opciones = Servicio::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'SE_CODIGO', 'SE_DESCRI');
    }

    public static function getListaPacientes()
    {
        $opciones = Paciente::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'PA_HISCLI', 'PA_APENOM');
    }

    public static function getListaServicios()
    {
        $opciones = Servicio::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'SE_CODIGO', 'SE_DESCRI');
    }

    public static function getListaAccionesTerapeuticas()
    {
        $opciones = Accionterapeutica::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'AC_COD', 'AC_DESCRI');
    }

    public static function getListaMedicos()
    {
        $opciones = Legajos::find()->andFilterWhere([
            'LE_ACTIVO' => 'T'])->andWhere(['<>', 'LE_MATRIC', ''])->asArray()->all();
        return ArrayHelper::map($opciones, 'LE_NUMLEGA', 'LE_APENOM');
    }

    public static function getListaObrasSociales()
    {
        $opciones = ObraSocial::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'OB_COD', 'OB_NOM');
    }

    

     public static function getListaUnidadesSanitarias()
    {
        $opciones = EntidadDerivadora::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'ED_COD', 'ED_DETALLE');
    }

    public function buscar_por_ud($params)
    {   

        $this->load($params);
        $query1 = Consumo_medicamentos_pacientes_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $this->monodroga,
            'CM_UNIDIAG' => $this->unidad_diag,
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

        $query1->groupBy(['CM_UNIDIAG','VA_CODMON']);

        $query1->select(["SUM(VA_CANTID) as cantidad","VA_CODMON as codmon",'CM_UNIDIAG  as unidiag']);



        $query2 = Devolucion_salas_paciente_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
        $query2->joinWith(['monod']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $this->monodroga,
            'DE_UNIDIAG' => $this->unidad_diag,
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

        $query2->groupBy(['DE_UNIDIAG','DV_CODMON']);

        $query2->select(["SUM(DV_CANTID) as cantidad","DV_CODMON as codmon",'DE_UNIDIAG as unidiag']);

        $query2 = (new \yii\db\Query())
          ->select(["sum(cantidad)*-1 as cantidad","codmon",'unidiag'])
          ->from(['salidas' => $query2]);

        $query2->groupBy(['codmon']);


        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","codmon",'AG_PRECIO','AG_NOMBRE','unidiag',"se_descri",'CONCAT(unidiag,codmon) as `codigo`'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery3->groupBy(['unidiag','codmon']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon ");  

         $unionQuery3->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = unidiag");  

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
            'key' => 'codigo',
        ]);


        return $dataProvider;
    }

    public function buscar_por_ud_droga($unidiag,$codmon)
    {   

        
        $query1 = Consumo_medicamentos_pacientes_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $codmon,
            'CM_UNIDIAG' => $unidiag,
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

        
        $query1->select(["CM_FECHA as fecha","CM_HORA as hora","VA_CANTID as cantidad","VA_CODMON as codmon",'CM_UNIDIAG  as unidiag']);



        $query2 = Devolucion_salas_paciente_renglones::find();
        $query2->joinWith(['devolucion_encabezado']);
        $query2->joinWith(['monod']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $codmon,
            'DE_UNIDIAG' => $unidiag,
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

       
        $query2->select(["DE_FECHA as fecha","DE_HORA as hora","(DV_CANTID*-1) as cantidad","DV_CODMON as codmon",'DE_UNIDIAG as unidiag']);

        // $query2 = (new \yii\db\Query())
        //   ->select(["sum(cantidad)*-1 as cantidad","codmon",'unidiag',"fecha","hora"])
        //   ->from(['salidas' => $query2]);

        //$query2->groupBy(['codmon']);


        $unionQuery3 = (new \yii\db\Query())
          ->select(["cantidad as total_consumo","codmon",'AG_PRECIO','AG_NOMBRE','unidiag',"se_descri","fecha","hora"])
          ->from(['salidas' => $query1->union($query2)]);

       // $unionQuery3->groupBy(['codmon']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon ");  

         $unionQuery3->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = unidiag");  

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);


        return $dataProvider;
    }

   public function buscar_paciente($params)
    {   

        $this->load($params);
        
        $query1 = Consumo_medicamentos_pacientes_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $this->monodroga,
            'CM_HISCLI' => $this->hiscli,
            'CM_CONDPAC' => 'I',
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

        $query1->select(["CM_FECHA as fecha","CM_HORA as hora","VA_CANTID as cantidad","VA_CODMON as codmon","CM_IDINTERNA as interna"]);

        $query2 = Devolucion_salas_paciente::find();
        $query2->joinWith(['vale_original']);
        $query2->joinWith(['renglones']);
        $query2->joinWith(['monodrogas']);
        $query2->andFilterWhere([
            'DE_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $this->monodroga,
            'DE_HISCLI' => $this->hiscli,
            'CM_CONDPAC' => 'I',
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
 
        $query2->select(["DE_FECHA as fecha","DE_HORA as hora","(DV_CANTID*-1) as cantidad","DV_CODMON as codmon","DE_IDINTERNA as interna"]);

         $unionQuery3 = (new \yii\db\Query())
          ->select(["cantidad as total_consumo","codmon",'(cantidad*AG_PRECIO) as valor_total',
                    'AG_NOMBRE',"fecha","hora",'interna','in_hiscli','pa_apenom',
                    'in_sala','in_serint','IN_UNDIAG','IN_FECING','IN_FECEGR','IN_CODOS','IN_DIAG1'])
          ->from(['salidas' => $query1->union($query2,true)]);

       

        $unionQuery3->join('LEFT JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon ");  

        $unionQuery3->join('LEFT JOIN', 'interna',
                 "interna.in_id = interna ");  

         $unionQuery3->join('LEFT JOIN', 'paciente',
                 "paciente.pa_hiscli = in_hiscli "); 

        

        $unionQuery3->orderBy(['interna'=>SORT_ASC,'fecha'=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);


        return $dataProvider;
    }

    public function buscar_servicio_cantped($params)
    {   

        $this->load($params);
        $query1 = Consumo_medicamentos_pacientes_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $this->monodroga,
            'CM_HISCLI' => $this->hiscli
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

        $query1->select(["CM_FECHA as fecha",'CM_HORA as hora',"VA_CODMON as codmon",
                        'AG_NOMBRE  as descripcion','VA_CANTID as cantidad_entregada',
                        '(VA_CANTID*AG_PRECIO) as valor','CM_SERSOL','CM_MEDICO']);

        $query2 = Devolucion_salas_paciente::find();
        $query2->joinWith(['vale_original']);
        $query2->joinWith(['renglones']);
        $query2->joinWith(['monodrogas']);
        $query2->andFilterWhere([
            'DE_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $this->monodroga,
            'DE_HISCLI' => $this->hiscli
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

         $query2->select(["DE_FECHA as fecha",'DE_HORA as hora',"DV_CODMON as codmon",
                        'AG_NOMBRE  as descripcion','DV_CANTID as cantidad_entregada',
                        '(DV_CANTID*AG_PRECIO*-1) as valor',
                        'CM_SERSOL','CM_MEDICO']);


       $unionQuery3 = (new \yii\db\Query())
          ->from(['consumo' => $query1->union($query2)]);

        $unionQuery3->orderBy(['fecha'=>SORT_ASC,'hora'=>SORT_ASC]);

         $unionQuery3->join('INNER JOIN', 'servicio',
                 "servicio.SE_CODIGO = CM_SERSOL");  
        $unionQuery3->join('INNER JOIN', 'legajos',
                "legajos.LE_NUMLEGA = CM_MEDICO");  
         


        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);


        return $dataProvider;
    }

     public function buscar_por_servicio_ud_at($params)
    {   

        $this->load($params);
        $query1 = Consumo_medicamentos_pacientes_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $this->monodroga,
            'CM_MEDICO' => $this->medsol,
            'CM_SERSOL' => $this->servicio,
            'AG_ACCION' => $this->accion_terapeutica
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

         $query1->select(["CM_SERSOL","CM_MEDICO",'CM_UNIDIAG','VA_CODMON as codmon','CM_HISCLI',
                         'CM_SUPERV',"CM_FECHA as fecha",'VA_CANTID as cantidad_entregada',
                        '(VA_CANTID*AG_PRECIO) as valor']);

        $query2 = Devolucion_salas_paciente::find();
        $query2->joinWith(['vale_original']);
        $query2->joinWith(['renglones']);
        $query2->joinWith(['monodrogas']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $this->monodroga,
            'CM_MEDICO' => $this->medsol,
            'CM_SERSOL' => $this->servicio,
            'AG_ACCION' => $this->accion_terapeutica,
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

        $query2->select(["CM_SERSOL","CM_MEDICO",'CM_UNIDIAG','DV_CODMON as codmon','CM_HISCLI',
                         'CM_SUPERV',"CM_FECHA as fecha",'DV_CANTID as cantidad_entregada',
                        '(DV_CANTID*AG_PRECIO*-1) as valor']);
        
       $unionQuery3 = (new \yii\db\Query())
          
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery3->select(["CM_SERSOL","se.SE_DESCRI as servicio_sol","CM_MEDICO",'me.LE_APENOM as medico_sol',
                        'CM_UNIDIAG','ud.SE_DESCRI as unidad_sol','codmon','AG_NOMBRE',
                        'CM_HISCLI','paciente.PA_APENOM as nombre_paciente',
                         'CM_SUPERV','su.LE_APENOM as supervisor_sol',
                         "fecha",'cantidad_entregada','valor']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon ");  

         $unionQuery3->join('INNER JOIN', 'servicio se',
                 "se.se_codigo = CM_SERSOL");  

        $unionQuery3->join('INNER JOIN', 'servicio ud',
                 "ud.se_codigo = CM_UNIDIAG");  
        
        $unionQuery3->join('INNER JOIN', 'paciente',
                 "paciente.PA_HISCLI = CM_HISCLI");  
        
         $unionQuery3->join('INNER JOIN', 'legajos su',
                "su.LE_NUMLEGA = CM_SUPERV");  
         
          $unionQuery3->join('INNER JOIN', 'legajos me',
                "me.LE_NUMLEGA = CM_MEDICO");  
        
        $unionQuery3->orderBy(['CM_SERSOL'=>SORT_ASC,'CM_MEDICO'=>SORT_ASC,
                                'CM_UNIDIAG'=>SORT_ASC,'codmon'=>SORT_ASC,]);         

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);


        return $dataProvider;
    }


     public function buscar_por_paciente_ambu_servicio($params)
    {   

        $this->load($params);
        $query1 = Consumo_medicamentos_pacientes_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $this->monodroga,
            'CM_SERSOL' => $this->servicio,
            'CM_HISCLI' => $this->hiscli,
            'CM_CONDPAC' => 'A',
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

         $query1->select(["CM_SERSOL","CM_MEDICO",'VA_CODMON as codmon','CM_HISCLI',
                         "CM_FECHA as fecha","CM_HORA as hora",'VA_CANTID as cantidad_entregada',
                        '(VA_CANTID*AG_PRECIO) as valor']);

        $query2 = Devolucion_salas_paciente::find();
        $query2->joinWith(['vale_original']);
        $query2->joinWith(['renglones']);
        $query2->joinWith(['monodrogas']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $this->monodroga,
            'DE_HISCLI' => $this->hiscli,
            'DE_SERSOL' => $this->servicio,
            'CM_CONDPAC' => 'A',
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

        $query2->select(["CM_SERSOL","CM_MEDICO",'DV_CODMON as codmon','CM_HISCLI',
                         "DE_FECHA as fecha","DE_HORA as hora",'DV_CANTID as cantidad_entregada',
                        '(DV_CANTID*-1*AG_PRECIO) as valor']);

                
       $unionQuery3 = (new \yii\db\Query())
          
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery3->select(["CM_SERSOL","se.SE_DESCRI as servicio_sol","CM_MEDICO",'me.LE_APENOM as medico_sol',
                       'codmon','AG_NOMBRE','CM_HISCLI','paciente.PA_APENOM as nombre_paciente',
                         "fecha",'hora','cantidad_entregada','valor']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon ");  

         $unionQuery3->join('INNER JOIN', 'servicio se',
                 "se.se_codigo = CM_SERSOL");  
        
         $unionQuery3->join('INNER JOIN', 'paciente',
                 "paciente.PA_HISCLI = CM_HISCLI");  
               
          $unionQuery3->join('INNER JOIN', 'legajos me',
                "me.LE_NUMLEGA = CM_MEDICO");  
        
        $unionQuery3->orderBy(['nombre_paciente'=>SORT_ASC,'servicio_sol'=>SORT_ASC,
                                'CM_MEDICO'=>SORT_ASC,'fecha'=>SORT_ASC]);         

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);


        return $dataProvider;
    }
    public function buscar_por_ambu_smu($params)
    {   

        $this->load($params);
        $query1 = Consumo_medicamentos_pacientes_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'VA_DEPOSITO' => $this->deposito,
            'VA_CODMON' => $this->monodroga,
            'CM_SERSOL' => '037',//SMU
            'CM_HISCLI' => $this->hiscli,
            'CM_CONDPAC' => 'A',
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

         $query1->select(["CM_SERSOL","CM_MEDICO",'VA_CODMON as codmon','CM_HISCLI',
                         "CM_FECHA as fecha","CM_HORA as hora",'VA_CANTID as cantidad_entregada',
                        '(VA_CANTID*AG_PRECIO) as valor']);

        $query2 = Devolucion_salas_paciente::find();
        $query2->joinWith(['vale_original']);
        $query2->joinWith(['renglones']);
        $query2->joinWith(['monodrogas']);
        $query2->andFilterWhere([
            'DV_DEPOSITO' => $this->deposito,
            'DV_CODMON' => $this->monodroga,
            'DE_HISCLI' => $this->hiscli,
            'DE_SERSOL' => '037',//SMU
            'CM_CONDPAC' => 'A',
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

        $query2->select(["CM_SERSOL","CM_MEDICO",'DV_CODMON as codmon','CM_HISCLI',
                         "DE_FECHA as fecha","DE_HORA as hora",'DV_CANTID as cantidad_entregada',
                        '(DV_CANTID*AG_PRECIO) as valor']);

                
       $unionQuery3 = (new \yii\db\Query())
          
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery3->select(["CM_SERSOL","se.SE_DESCRI as servicio_sol","CM_MEDICO",'me.LE_APENOM as medico_sol',
                       'codmon','AG_NOMBRE','CM_HISCLI','paciente.PA_APENOM as nombre_paciente',
                         "fecha",'hora','cantidad_entregada','valor']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codmon ");  

         $unionQuery3->join('INNER JOIN', 'servicio se',
                 "se.se_codigo = CM_SERSOL");  
        
         $unionQuery3->join('INNER JOIN', 'paciente',
                 "paciente.PA_HISCLI = CM_HISCLI");  
               
          $unionQuery3->join('INNER JOIN', 'legajos me',
                "me.LE_NUMLEGA = CM_MEDICO");  
        
        $unionQuery3->orderBy(['nombre_paciente'=>SORT_ASC,'servicio_sol'=>SORT_ASC,
                                'CM_MEDICO'=>SORT_ASC]);         

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);


        return $dataProvider;
    }

}

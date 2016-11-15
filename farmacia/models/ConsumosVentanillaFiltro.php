<?php

namespace farmacia\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class ConsumosVentanillaFiltro extends ReporteFiltro
{
   public $unidad_diag,$droga,$hiscli,$servicio,$accion_terapeutica,$medsol,$programas,$obrasocial,$entidad;

    public function rules()
    {
        return [
           // [['deposito','hiscli'], 'required'],
            [['deposito','clases','monodroga','periodo_inicio','periodo_fin','unidad_diag','droga','hiscli','servicio','accion_terapeutica','programas','obrasocial','entidad'], 'safe'],
        ];
    }


     public function attributeLabels()
    {
        
        $labels= [
            'hiscli' => 'Historia Clínica',
            'accion_terapeutica' => 'Acción Terapeutica',
            'unidad_diag' => 'Unidad de Diagnóstico',
            'medsol' => 'Médico Solicitante',
            'programas' => 'Programas',
            'obrasocial' => 'Obra Social',
            'unidad_sanitaria' =>'Unidad Sanitaria',
        ];

        return array_merge(parent::attributeLabels(),$labels);
    }

     public static function getListaProgramas()
    {
        $opciones = Programa::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'PR_CODIGO', 'PR_NOMBRE');
    }


    public static function getListaEntidades()
    {
        $opciones = EntidadDerivadora::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'ED_COD', 'ED_DETALLE');
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

    
     public function buscar_por_demanda($params)
    {   

        $this->load($params);
        $query1 = Ambulatorios_ventanilla_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'AM_CODMON' => $this->monodroga,
          'AM_HISCLI' => $this->hiscli,
        ]);
                

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'AM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'AM_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);
        $query1->andFilterWhere(['IN', 'AM_PROG', $this->programas]);

        $query1->groupBy(['AM_CODMON']);

        $query1->select(["SUM(AM_CANTPED) as cantidad_pedida","SUM(AM_CANTENT) as cantidad_entregada",
                        "AM_CODMON",'AG_NOMBRE']);
        $query1 = (new \yii\db\Query())
          
          ->from(['salidas' => $query1]);
       
        

          $dataProvider = new ActiveDataProvider([
            'query' => $query1,
            'key' => 'AM_CODMON',
        ]);
        return $dataProvider;
    }

    
     public function buscar_por_demanda_droga($droga)
    {   

        $query1 = Ambulatorios_ventanilla_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
           'AM_CODMON' => $droga,
           'AM_HISCLI' => $this->hiscli,
        ]);
                

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'AM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'AM_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);
        $query1->andFilterWhere(['IN', 'AM_PROG', $this->programas]);

        //$query1->groupBy(['AM_CODMON']);

        $query1->select(["AM_FECHA","AM_CANTPED as cantidad_pedida","AM_CANTENT as cantidad_entregada",
                        "AM_CODMON",'AG_NOMBRE','AM_HISCLI','AM_PROG']);
        $query1 = (new \yii\db\Query())
          
          ->from(['salidas' => $query1]);
       
        $query1->join('INNER JOIN', 'paciente',
                 "paciente.pa_hiscli = AM_HISCLI "); 


       $query1->join('INNER JOIN', 'programa',
                 "programa.PR_CODIGO = AM_PROG "); 

          $dataProvider = new ActiveDataProvider([
            'query' => $query1,
            
        ]);
        return $dataProvider;
    }
    public function buscar_por_ventanilla_unidad($params)
    {   

        $this->load($params);
        $query1 = Ambulatorios_ventanilla_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'AM_CODMON' => $this->monodroga,
            'AM_HISCLI' => $this->hiscli,
        ]); 
                

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'AM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'AM_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);
        $query1->andFilterWhere(['IN', 'AM_PROG', $this->programas]);

        $query1->groupBy(['AM_CODMON']);

        $query1->select(["SUM(AM_CANTPED) as cantidad_pedida","SUM(AM_CANTENT) as cantidad_entregada",
                        "AM_CODMON",'AG_NOMBRE','AM_ENTIDER', 'AM_HISCLI']);
        $query1 = (new \yii\db\Query())
          
          ->from(['salidas' => $query1]);
       $query1->join('INNER JOIN', 'paciente',
                 "paciente.pa_hiscli = AM_HISCLI "); 

       $query1->join('INNER JOIN', 'enti_der',
                 "enti_der.ED_COD = AM_ENTIDER "); 

        $dataProvider = new ActiveDataProvider([
            'query' => $query1,
            'key' => 'AM_CODMON',
        ]);

        $dataProvider->setKeys('AM_CODMON');
        
        return $dataProvider;
    }

    public function buscar_por_ambu_ooss($params)
    {   

        $this->load($params);
        $query1 = Ambulatorios_ventanilla_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'AM_CODMON' => $this->monodroga,
            'AM_HISCLI' => $this->hiscli,
        ]); 
                

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'AM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'AM_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);
        $query1->andFilterWhere(['IN', 'AM_PROG', $this->programas]);

        //$query1->groupBy(['AM_CODMON']);

        $query1->select(["AM_FECHA","AM_CANTENT as cantidad_entregada","(AM_CANTENT*AG_PRECIO) as `valor`",
                        "AM_CODMON",'AG_NOMBRE','AM_HISCLI','AM_PROG']);
        $query1 = (new \yii\db\Query())
          
          ->from(['salidas' => $query1]);
       $query1->join('INNER JOIN', 'paciente',
                 "paciente.pa_hiscli = AM_HISCLI "); 

       $query1->join('INNER JOIN', 'programa',
                 "programa.PR_CODIGO = AM_PROG "); 

       $query1->join('INNER JOIN', 'obrasoci',
                 "paciente.PA_CODOS = OB_COD ");

       $query1->orderBy(["OB_COD"=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query1,
        ]);


        return $dataProvider;
    }

    public function buscar_por_ambu_valorizado($params)
    {   

        $this->load($params);
        $query1 = Ambulatorios_ventanilla_renglones::find();
        $query1->joinWith(['vale']);
        $query1->joinWith(['monodroga']);
        $query1->andFilterWhere([
            'AM_CODMON' => $this->monodroga,
            'AM_HISCLI' => $this->hiscli,
        ]); 
                

         if (isset($this->periodo_inicio) && !$this->periodo_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->periodo_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query1->andFilterWhere(['>=', 'AM_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->periodo_fin) && !$this->periodo_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->periodo_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query1->andFilterWhere(['<=', 'AM_FECHA', $fecha_fin_format]);
         }

        $query1->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);
        $query1->andFilterWhere(['IN', 'AM_PROG', $this->programas]);

        //$query1->groupBy(['AM_CODMON']);

        $query1->select(["AM_FECHA","AM_CANTENT as cantidad_entregada","(AM_CANTENT*AG_PRECIO) as `valor`",
                        "AM_CODMON",'AG_NOMBRE','AM_HISCLI','AM_PROG']);
        $query1 = (new \yii\db\Query())
          
          ->from(['salidas' => $query1]);
       $query1->join('INNER JOIN', 'paciente',
                 "paciente.pa_hiscli = AM_HISCLI "); 

       $query1->join('INNER JOIN', 'programa',
                 "programa.PR_CODIGO = AM_PROG "); 

        $query1->orderBy(["AM_FECHA"=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query1,
        ]);


        return $dataProvider;
    }
}

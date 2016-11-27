<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class FiltroAbc extends FiltroReporte
{
    public $activos,$servicio;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','activos','fecha_inicio','fecha_fin','servicio','articulo'], 'safe'],
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

        $query->joinWith(['articulo']);
        $query->joinWith(['codigo']);
        
        

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
        // grid filtering conditions
         $query->andFilterWhere(['AG_ACTIVO'=>$this->activos,'DM_VALIDO'=>0]);

        $query->andFilterWhere(['like', 'DM_CODART', $this->articulo])
            ->andFilterWhere(['like', 'DM_DEPOSITO', $this->deposito])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query->groupBy(['DM_CODART']);

        $query->select(['SUM(DM_CANT*DM_SIGNO) as consumo',
                        'SUM(DM_CANT*DM_SIGNO*AG_PRECIO) as consumo_valor',
                        '`dc_mov_dia`.*']);

        $query->orderBy(['consumo_valor'=>SORT_DESC]);    
       return $dataProvider;

    }

    public function abc_servicio($params)
    {   
  
        $this->load($params);

        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->joinWith(['articulo']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
             'PR_CODART' => $this->articulo,
             'PE_SERSOL' => $this->servicio,

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
            'DE_SERSOL' => $this->servicio,
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

        $query2->select(["SUM(PR_CANTID)*-1 as cantidad","PR_CODART as codart",'DE_SERSOL as servicio']);

 
        $unionQuery3 = (new \yii\db\Query())
          ->select(["sum(cantidad) as total_consumo","sum(cantidad*AG_PRECIO) as consumo_valor",
                    "codart",'AG_NOMBRE','AG_PRECIO','servicio','se_descri'])
          ->from(['salidas' => $query1->union($query2)]);

        $unionQuery3->groupBy(['codart','servicio']);

        $unionQuery3->join('INNER JOIN', 'artic_gral',
                 "artic_gral.ag_codigo = codart ");  

        $unionQuery3->join('INNER JOIN', 'servicio',
                 "servicio.se_codigo = servicio");  

        $unionQuery3->orderBy(['se_descri'=>SORT_ASC,'consumo_valor'=>SORT_DESC]); 

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery3,
        ]);
       
        return $dataProvider;
    }

    public function getActivo_descripcion(){
        if ($this->activos=='T')
            return "Activos";
        elseif ($this->activos=='F')
            return 'Inactivos';
        else
            return "Todos";
    }

    public function getServicio_descripcion(){
        if ($servicio=Servicio::findOne(['SE_CODIGO'=>$this->servicio]))
            return "[$this->servicio]-".$servicio->SE_DESCRI;
        else
            return '';
    }

}

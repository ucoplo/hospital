<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider ;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `deposito_central\models\Remito_Adquisicion`.
 */
class FiltroReposicion extends FiltroReporte
{
    public $meses;

    public function rules()
    {
        return [
            [['deposito'], 'required', 'on' => 'prevision'],
            [['deposito'], 'required', 'on' => 'salida_valorizada'],
            [['deposito','meses'], 'required', 'on' => 'pendientes'],
            [['deposito','clases','articulo','meses'], 'safe'],
            [['meses'], 'number'],
        ];
    }

    
    public function buscar_pendientes_entrega($params)
    {
        $query = ArticGral::find();

        $this->load($params);

        $query->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->articulo])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

         $query2 = (new \yii\db\Query())
          ->select(['AG_CODIGO','AG_NOMBRE','AG_STACT','SUM(PE_CANT) as pendientes'])
          ->from(['articulos' => $query]);

   
        $query2->join('INNER JOIN', 'pead_mov',
                 "pead_mov.pe_codart = ag_codigo and pead_mov.pe_deposito = ag_deposito and 
                pead_mov.pe_cant>0");  

        $query2->groupBy(['AG_CODIGO']);


        $articulos = $query2->all();
        
        $articulos_consumo= [];
        foreach ($articulos as $key => $art) {

            $consumo_diario = $this->consumo_medio_diario($art['AG_CODIGO']);
             $art['consumo_medio'] = $consumo_diario;
            $articulos_consumo[] = $art;
            
           
        }

        $dataProvider =  new ArrayDataProvider([
            'allModels' => $articulos_consumo,
        ]);
     
        return $dataProvider;

    }

    //Consumo Diario de los n meses ingresados
    public function consumo_medio_diario($codart){

         $hoy=date('Y-m-d');
        $fecha_inicio = date('Y-m-d', strtotime("-$this->meses month", strtotime( $hoy ) ));
        $consumo = 0;

        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $codart,
        ]);
        
        $query1->andFilterWhere(['>=','PE_FECHA', $fecha_inicio]);

        $consumo += $query1->sum('PR_CANTID');

       
        $query1 = Devolucion_salas_renglones::find();

        $query1->joinWith(['devolucion_encabezado']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $codart,
        ]);

        $query1->andFilterWhere(['>=','DE_FECHA', $fecha_inicio]);

        $consumo -= $query1->sum('PR_CANTID');
        
        $segundos=strtotime('now') - strtotime($fecha_inicio);
        
        $dias=intval($segundos/60/60/24);
        return $consumo/$dias;

    }


    //Consumo Diario del ultimo año
    public function consumo_medio_anual_prevision($codart){
        $fecha_inicio =  date('Y-m-d',strtotime(date('Y-01-01')));

        $consumo = $this->salida_anual($codart);
        
        $segundos=strtotime('now') - strtotime($fecha_inicio);
        
        $dias=intval($segundos/60/60/24);
        return $consumo/$dias;

    }

    public function salida_anual($codart){
               $fecha_inicio =  date('Y-m-d',strtotime(date('Y-01-01')));

        $consumo = 0;

        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $codart,
        ]);
        
        $query1->andFilterWhere(['>=','PE_FECHA', $fecha_inicio]);

        $consumo += $query1->sum('PR_CANTID');

       
        $query1 = Devolucion_salas_renglones::find();

        $query1->joinWith(['devolucion_encabezado']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $codart,
        ]);

        $query1->andFilterWhere(['>=','DE_FECHA', $fecha_inicio]);

        $consumo -= $query1->sum('PR_CANTID');

        return $consumo;

    }


    //Consumo Diario de los n meses ingresados
    public function consumo_medio_puntual_prevision($codart){

         $hoy=date('Y-m-d');
        $fecha_inicio = date('Y-m-d', strtotime("-3 month", strtotime( $hoy ) ));
        $consumo = 0;

        $query1 = Planilla_entrega_renglones::find();
        $query1->joinWith(['remito']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $codart,
        ]);
        
        $query1->andFilterWhere(['>=','PE_FECHA', $fecha_inicio]);

        $consumo += $query1->sum('PR_CANTID');

       
        $query1 = Devolucion_salas_renglones::find();

        $query1->joinWith(['devolucion_encabezado']);
        $query1->andFilterWhere([
            'PR_DEPOSITO' => $this->deposito,
            'PR_CODART' => $codart,
        ]);

        $query1->andFilterWhere(['>=','DE_FECHA', $fecha_inicio]);

        $consumo -= $query1->sum('PR_CANTID');
        
        $segundos=strtotime('now') - strtotime($fecha_inicio);
        
        $dias=intval($segundos/60/60/24);
        return $consumo/$dias;

    }

    //Consumo Diario de los n meses ingresados
    public function pendientes_entrega($codart){

        $pendientes = 0;

        $query1 = Pedido_adquisicion_renglones::find();
        
        $query1->andFilterWhere([
            'PE_DEPOSITO' => $this->deposito,
            'PE_CODART' => $codart,
        ]);
        
        $query1->andFilterWhere(['>','PE_CANT', 0]);

        $pendientes = $query1->sum('PE_CANT');
       

        return (isset($pendientes))?$pendientes:0;

    }


    public function buscar_prevision($params)
    {
        $fecha_inicio =  date('Y-m-d',strtotime(date('Y-01-01')));

        $query = ArticGral::find();

        $this->load($params);
        $query->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->articulo])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query2 = (new \yii\db\Query())
          ->select(['AG_CODIGO','AG_NOMBRE','AG_STACT'])
          ->from(['articulos' => $query]);
 
        $articulos = $query2->all();
        
        $articulos_prevision= [];
        foreach ($articulos as $key => $art) {

            $art['consumo_medio'] = $this->consumo_medio_anual_prevision($art['AG_CODIGO']);
            $art['consumo_puntual'] = $this->consumo_medio_puntual_prevision($art['AG_CODIGO']);
            $art['salida_anual'] = $this->salida_anual($art['AG_CODIGO']);
            $art['pendientes_entrega'] = $this->pendientes_entrega($art['AG_CODIGO']);

            if ($art['consumo_puntual']>0){
                $art['prevision_dias'] = ($art['pendientes_entrega']+$art['AG_STACT'])/$art['consumo_puntual'];
                $art['prevision_existencia'] = $art['AG_STACT']/$art['consumo_puntual'];
                $art['prevision_pendientes'] = $art['pendientes_entrega']/$art['consumo_puntual'];
            }elseif ($art['consumo_medio']>0){
                $art['prevision_dias'] = ($art['pendientes_entrega']+$art['AG_STACT'])/$art['consumo_medio'];
                $art['prevision_existencia'] = $art['AG_STACT']/$art['consumo_medio'];
                $art['prevision_pendientes'] = $art['pendientes_entrega']/$art['consumo_medio'];
            }else{
                $art['prevision_dias'] = 0;
                $art['prevision_existencia'] = 0;
                $art['prevision_pendientes'] = 0;
            }

            $articulos_prevision[] = $art;
        }
      
         $dataProvider =  new ArrayDataProvider([
            'allModels' => $articulos_prevision,
        ]);

      
        return $dataProvider;

    }

    public function buscar_salida_valorizada($params)
    {
        $fecha_inicio =  date('Y-m-d',strtotime(date('Y-01-01')));

        $query = ArticGral::find();

        $this->load($params);
        $query->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
        ]);

        $query->andFilterWhere(['like', 'AG_CODIGO', $this->articulo])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases]);

        $query2 = (new \yii\db\Query())
          ->select(['AG_CODIGO','AG_NOMBRE','AG_STACT','AG_PRECIO'])
          ->from(['articulos' => $query]);
 
        $articulos = $query2->all();
        
        $articulos_prevision= [];
        foreach ($articulos as $key => $art) {

            
            $art['salida_anual'] = $this->salida_anual($art['AG_CODIGO']);
            $art['salida_valorizada'] = $art['salida_anual']*$art['AG_PRECIO'];
            $art['existencia_valorizada'] = $art['AG_STACT']*$art['AG_PRECIO'];

            
            $articulos_prevision[] = $art;
        }
      
         $dataProvider =  new ArrayDataProvider([
            'allModels' => $articulos_prevision,
        ]);

      
        return $dataProvider;

    }
    

}

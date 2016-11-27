<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Remito_adquisicion;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `farmacia\models\Remito_Adquisicion`.
 */
class FiltroIngresos extends FiltroReporte
{
   public $proveedor,$agrupado;

    public function rules()
    {
        return [
            [['deposito'], 'required'],
            [['deposito','clases','articulo','fecha_inicio','fecha_fin','proveedor','agrupado'], 'safe'],
        ];
    }

    
    public function buscar($params)
    {
        $query = Remito_adquisicion_renglones::find();

        // add conditions that should always apply here
        $query->joinWith(['remito']);
        $query->joinWith(['articulo']);
        $query->joinWith(['orden_compra']);

        

        
        $this->load($params);
       
         if (isset($this->fecha_inicio) && !$this->fecha_inicio=='') {
             $nueva_fecha_inicio = \DateTime::createFromFormat('d-m-Y',  $this->fecha_inicio);
             $fecha_inicio_format = $nueva_fecha_inicio->format('Y-m-d');            
             $query->andFilterWhere(['>=', 'RA_FECHA', $fecha_inicio_format]);
         }

         if (isset($this->fecha_fin) && !$this->fecha_fin=='') {
             $nueva_fecha_fin = \DateTime::createFromFormat('d-m-Y',  $this->fecha_fin);
             $fecha_fin_format = $nueva_fecha_fin->format('Y-m-d');
             $query->andFilterWhere(['<=', 'RA_FECHA', $fecha_fin_format]);
         }


        $query->andFilterWhere(['AR_CODART' => $this->articulo])
            ->andFilterWhere(['like', 'RA_DEPOSITO', $this->deposito])
            ->andFilterWhere(['IN', 'AG_CODCLA', $this->clases])
            ->andFilterWhere(['IN', 'OC_PROVEED', $this->proveedor]);
  
        $query->join('LEFT JOIN', 'proveedores',
                 "proveedores.pr_codigo = OC_PROVEED");  
       
        $query->select(["AR_CODART","PR_CODIGO","PR_RAZONSOC","RA_FECHA","AR_CANTID","AR_PRECIO","AG_NOMBRE"]);
        
        $query1 = (new \yii\db\Query())
          ->select(["AR_CODART","PR_CODIGO","PR_RAZONSOC","RA_FECHA","AR_CANTID","AR_PRECIO","AG_NOMBRE"])
          ->from(['ingresos' => $query]);

        if ($this->agrupado=='A'){
            $query1->orderBy(['AR_CODART'=>SORT_ASC,'RA_FECHA'=>SORT_ASC,'PR_RAZONSOC'=>SORT_ASC]);
        }else{
             $query1->orderBy(['PR_RAZONSOC'=>SORT_ASC,'RA_FECHA'=>SORT_ASC,'AR_CODART'=>SORT_ASC]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query1,
        ]);
        return $dataProvider;
    }

    
     public function getProveedor_descripcion(){
        if ($articulo=Proveedores::findOne(['PR_CODIGO'=>$this->proveedor]))
            return "[$this->proveedor]-".$articulo->PR_RAZONSOC;
        else
            return '';
    }
   
}

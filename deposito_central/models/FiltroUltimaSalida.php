<?php

namespace deposito_central\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deposito_central\models\Remito_adquisicion;
use yii\helpers\ArrayHelper;

/**
 * Remito_AdquisicionSearch represents the model behind the search form about `deposito_central\models\Remito_Adquisicion`.
 */
class FiltroUltimaSalida extends FiltroReporte
{
    public $limite1,$limite2,$limite3,$limite4;
    public $nombre1,$nombre2,$nombre3,$nombre4;
    public $fecha_hasta;

    public function rules()
    {
        return [
            [['fecha_hasta','deposito','limite1','limite2','limite3','limite4','nombre1','nombre2','nombre3','nombre4'], 'required'],
            [['limite1','limite2','limite3','limite4'], 'number'],
            [['deposito','limite1','limite2','limite3','limite4','nombre1','nombre2','nombre3','nombre4'], 'safe'],
        ];
    }

     public function attributeLabels()
    {
        return [
            'nombre1' => 'Clase 1',
            'nombre2' => 'Clase 2',
            'nombre3' => 'Clase 3',
            'nombre4' => 'Clase 4',
            'deposito'=> 'Depósito',
            'limite1' => 'Días 1',
            'limite2' => 'Días 2',
            'limite3' => 'Días 3',
            'limite4' => 'Días 4',

            
        ];
    }
    
    public function buscar($params)
    {
       $query = ArticGral::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
       

        $this->load($params);

        $query->andFilterWhere([
            'AG_DEPOSITO' => $this->deposito,
            'AG_ACTIVO' => 'T', //Articulo Activo
            
        ]);
         if (isset($this->limite1) && !$this->limite1=='') {
            $fecha1 = date ( 'Y-m-d' ,strtotime ( "-$this->limite1 day" , strtotime ( $this->fecha_hasta  ) )) ;
            $fecha2 = date ( 'Y-m-d' ,strtotime ( "-$this->limite2 day" , strtotime (  $this->fecha_hasta ) )) ;
            $fecha3 = date ( 'Y-m-d' ,strtotime ( "-$this->limite3 day" , strtotime (  $this->fecha_hasta) )) ;
            $fecha4 = date ( 'Y-m-d' ,strtotime ( "-$this->limite4 day" , strtotime (  $this->fecha_hasta ) )) ;
        
            $query->select(["CASE 
                          WHEN (AG_ULTSAL>'$fecha1') THEN '$this->nombre1'
                          WHEN (AG_ULTSAL>'$fecha2') THEN '$this->nombre2'
                          WHEN (AG_ULTSAL>'$fecha3') THEN '$this->nombre3'
                         ELSE '$this->nombre4'
                         END as clasifica", "DATEDIFF('$this->fecha_hasta',AG_ULTSAL)
                           as dias_salida",
                        '`artic_gral`.*']);
        }
        $query->andFilterWhere(['<', 'AG_ULTSAL', $this->fecha_hasta]);
          
        return $dataProvider;


    }

  
}

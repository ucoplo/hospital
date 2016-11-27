<?php

use yii\helpers\Html;
use kartik\grid\GridView;



?>

<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'showPageSummary' => true,
        'pjax'=>true,
        'id'=>'droga_'.$id,
        'summary'=>"",
        'columns' => [
             [
                'attribute'=>'codart', 
                'label' => "Código",
                'group'=>true,
                'groupedRow'=>true,  
                 'value'=>function ($model, $key, $index, $widget) { 
                    return "Artículo: ".$model['codart'].' - '.$model['AG_NOMBRE'];
                },
            ],
            [
                'attribute'=>'fecha', 
                'label' => 'Fecha',
                'format'=>['date', 'php:d-m-Y'], 
                 'width'=>'50px',
            ],
           
             [
                'attribute'=>'AG_PRES', 
                'label' => "Presentación",
            ],
            [
                'attribute' => 'destinatario',
                'label' => "Destinatario",
            ],
             [
                'attribute'=>'total_consumo', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Cantidad",
                'pageSummary'=>true,
            ],
             [
                'attribute'=>'valor_total', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Valor Compra",
                'pageSummary'=>true,
            ],
          
            
           
           
        ],
    ]);?>

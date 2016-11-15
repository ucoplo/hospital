<?php

use yii\helpers\Html;
use kartik\grid\GridView;



echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'showPageSummary' => true,
        'pjax'=>true,
        'id'=>'droga_'.$id,
        'summary'=>"",
        'columns' => [
            [
                'attribute' => 'AM_CODMON',
                'label' => 'Código',
                'group' => true,
                'groupedRow'=>true,     
            
                 'value'=>function ($model, $key, $index, $widget) { 
                    return "Droga: ".$model['AM_CODMON'].' - '.$model['AG_NOMBRE'];
                },
            ],
            [
                'attribute'=>'AM_FECHA', 
                'label' => 'Fecha',
                'format'=>['date', 'php:d-m-Y'], 
                 'width'=>'50px',
            ],
             [
                'attribute'=>'AM_HISCLI', 
                'label' => 'HC Nº',
                 'width'=>'50px',
            ],
             [
                'attribute'=>'PA_APENOM', 
                'label' => 'Apellido y Nombres',
            ],
             [
                'attribute'=>'AM_PROG', 
                'label' => 'Programa',
                 'value'=>function ($model, $key, $index, $widget) { 
                    return $model['PR_NOMBRE'];
                },
            ],
             [
                'attribute'=>'cantidad_entregada', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Consumo",
            ],
            [
                'class'=>'kartik\grid\FormulaColumn',
                'header'=>'Demanda Insatisfecha',
                'value'=>function ($model, $key, $index, $widget) { 
                    $p = compact('model', 'key', 'index');
                    return $widget->col(7, $p) - $widget->col(5, $p);
                },
                'mergeHeader'=>true,
                'width'=>'150px',
                'hAlign'=>'right',
                'format'=>['decimal', 2],
               
            ],
             [
                'attribute'=>'cantidad_pedida', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Consumo con demanda insatisfecha",
                //'visible' => false
            ],
            
           
           
        ],
    ]);?>

<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use farmacia\models\ArticGral;
use farmacia\models\Servicio;

$codmon_nombre = empty($codmon) ? '' : ArticGral::findOne($codmon);
$unidiag_nombre = empty($unidiag) ? '' : Servicio::findOne($unidiag);
?>

<div class="row " >
        <div class="col-md-6">
           <?php echo Html::label('Unidad de Diagnóstico: ', 'unidiag').Html::label( $unidiag_nombre->SE_DESCRI, 'unidiag');?>
        </div>
         
</div>
<div class="row " >
        <div class="col-md-6">
            <?php echo Html::label('Droga: ', 'codmon').Html::label($codmon_nombre->AG_NOMBRE, 'codmon');?>
        </div>
         <div class="col-md-6">
            <?php echo Html::label('Presentación: ', 'pres').Html::label($codmon_nombre->AG_PRES, 'pres');?>
        </div>
</div>
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'showPageSummary' => true,
        'pjax'=>true,
        'id'=>'droga_'.$id,
        'summary'=>"",
        'columns' => [
            [
                'attribute'=>'fecha', 
                'label' => 'Fecha',
                'format'=>['date', 'php:d-m-Y'], 
                 'width'=>'50px',
            ],
            [
                'attribute'=>'hora', 
                'label' => 'Hora',
                'width'=>'50px',
            ],
           [
                'attribute'=>'total_consumo', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Cantidad",
            ],
             [
                'attribute'=>'AG_PRECIO', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Valor Compra",
            ],
            [
                'class'=>'kartik\grid\FormulaColumn',
                'header'=>'Valor Total',
                'value'=>function ($model, $key, $index, $widget) { 
                    $p = compact('model', 'key', 'index');
                    return $widget->col(2, $p) * $widget->col(3, $p);
                },
                'mergeHeader'=>true,
                'width'=>'150px',
                'hAlign'=>'right',
                'format'=>['decimal', 2],

            ],
            
           
           
        ],
    ]);?>

<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Consumos por Vale o Planillas de Salas con Demanda Insatisfecha';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumos-vales-planillas-index">
 <h3><?= Html::encode($this->title) ?></h3>
<div class="consumos-vales-planillas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['consumo_por_vales_planillas'],
        'method' => 'get',
    ]); ?>
    
    <?= $form->field($searchModel, 'deposito')->dropDownList($searchModel->listaDepositos, ['disabled'=>!$filtro,'prompt' => 'Seleccione Depósito']);?>

    <?php
            $layout3 = <<< HTML
                <span class="input-group-addon">Desde: </span>
                {input1}
                <span class="input-group-addon">Hasta: </span>
                {input2}
                <span class="input-group-addon kv-date-remove">
                    <i class="glyphicon glyphicon-remove"></i>
                </span>
HTML;
?>
    <div class="row">
        <div class="col-md-6">
       <label class="control-label">Fecha</label>

        <?= DatePicker::widget([
                'type'=>DatePicker::TYPE_RANGE,
                'model' => $searchModel,
                'disabled' => !$filtro,
                /*'options' => ['placeholder' => 'Desde'],
                'options2' => ['placeholder' => 'Hasta'],*/
                'attribute' => 'periodo_inicio',
                'attribute2' => 'periodo_fin',
                'layout' => $layout3,
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'autoclose'=>true
                ]
            ]);
        ?>
        <div class="help-block"></div>
        
        </div>
    </div>

    
     <?= $form->field($searchModel, 'clases')->widget(Select2::classname(), [
        'data' => $searchModel->listaClases,
        'disabled' => !$filtro,
        'options' => ['placeholder' => '','multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>
    <?= $form->field($searchModel, 'servicios')->widget(Select2::classname(), [
        'data' => $searchModel->listaServicios,
        'disabled' => !$filtro,
        'options' => ['placeholder' => '','multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <div class="form-group">
        <?php if ($filtro) {echo Html::submitButton('Filtrar', ['class' => 'btn btn-primary']);} ?>
     
    </div>
    

    <?php ActiveForm::end(); ?>

</div>
    
   

<?php 
   if (!$filtro){ 
        
        $pdfHeader = [
            'L' => [
                'content' => '',
                 'font-size' => 15,
                'color' => '#377333'
            ],
            'C' => [
                'content' => " <div><img src='images/header_label.png' alt=''></div><div>Consumos por Vale o Planillas de Salas con Demanda Insatisfecha</div>",
                'font-size' => 15,
                'color' => '#333333',
                'height'=> '150px',
                'display' => 'block'
            ],
            'R' => [
                'content' => '',
                'font-size' => 15,
                'color' => '#333333'
            ],
           // 'line' => true,
        ];
        $pdfFooter = [
            'L' => [
                'content' => 'Farmacia '.date("d-m-Y h:i"),
                'font-size' => 8,
                'font-style' => 'B', 
                'color' => '#999999'
            ],
            'R' => [
                'content' => '[ {PAGENO} ]',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' => 'serif',
                'color' => '#333333'
            ],
            'line' => true,
        ];
?>
<div class="form-group">
    
        <?= Html::a('Volver',['consumo_por_vales_planillas'],array('class'=>'btn btn-primary'));?>
    </div>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'showPageSummary' => true,
        'pjax'=>true,
         'striped'=>true,
         'hover'=>true,
         
    
        'panel'=>['type'=>'primary', 'heading'=>'Consumos por Vale o Planillas de Salas con Demanda Insatisfecha'],
        'toolbar'=>[
           
            '{export}',
            '{toggleData}'
        ],
        'export' => ['messages' => ['confirmDownload'=>'¿Exporta el listado?',
                                    
                                    'downloadProgress'=>'Generando. Por favor espere...',

                                    ],
                    'header' => '',
        
        ],
        'exportConfig'=>[
             GridView::CSV => [
                'filename' => 'consumos_vales_planillas',
            ],
            GridView::PDF => [
                   
                    'filename' => 'consumos_vales_planillas',
                    'config' => [
                        'marginTop' => 53,
                        'cssInline' =>'.kv-heading-1{font-size:18px;color:red;}',
                        'format' => 'A4-P',
                        'methods' => [
                            'SetHeader' => [
                                ['odd' => $pdfHeader, 'even' => $pdfHeader]
                            ],
                            'SetFooter' => [
                                ['odd' => $pdfFooter, 'even' => $pdfFooter]
                            ],
                        ],
                    ]
                ],
            ],
        'columns' => [
             ['class'=>'kartik\grid\SerialColumn'],
             [
                'attribute' => 'codmon',
                'label' => 'Código',
                'width' => '50px',
            ],
             [
                'attribute'=>'descripcion', 
                'label' => "Denominación",
            ],
             [
                'attribute'=>'cantidad_pedida', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'200px', 
                'label' => "Cantidad Pedida",
            ],
             [
                'attribute'=>'cantidad_entregada', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'200px', 
                'label' => "Cantidad Entregada",
            ],
            [
                'class'=>'kartik\grid\FormulaColumn',
                'header'=>'Demanda Insatisfecha',
                'value'=>function ($model, $key, $index, $widget) { 
                    $p = compact('model', 'key', 'index');
                    return $widget->col(3, $p) - $widget->col(4, $p);
                },
                'mergeHeader'=>true,
                'width'=>'150px',
                'hAlign'=>'right',
                'format'=>['decimal', 2],
               
            ],
           
        ],
    ]); ?>
<?php Pjax::end(); }?></div>

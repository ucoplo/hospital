<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use farmacia\models\Paciente;
use \kartik\grid\ExpandRowColumn;
use yii\helpers\Url;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Consumos Ventanillas con demanda insatisfecha';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumos-ventanilla-demanda-index">
 
 <h3><?= Html::encode($this->title) ?></h3>

<div class="consumos-ventanilla-demanda-search">

    <?php $form = ActiveForm::begin([
        'action' => ['consumos_ventanilla_demanda'],
        'method' => 'get',
        'id' => 'formVentanillaDemanda'
    ]); ?>
    
    

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

    <?= $form->field($searchModel, 'programas')->widget(Select2::classname(), [
        'data' => $searchModel->listaProgramas,
        'disabled' => !$filtro,
        'options' => ['placeholder' => '','multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($searchModel, 'monodroga')->widget(Select2::classname(), [
        'data' => $searchModel->listaMonodrogas,
        'disabled' => !$filtro,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?php  // Get the initial city description
    $url = \yii\helpers\Url::to(['pacientelist']);
    $pacienteNombre = empty($searchModel->hiscli) ? '' : Paciente::findOne($searchModel->hiscli)->PA_APENOM;
     
    echo $form->field($searchModel, 'hiscli')->widget(Select2::classname(), [
        'initValueText' => $pacienteNombre, // set the initial display text
        'options' => ['placeholder' => ''],
        'disabled' => !$filtro,
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Esperando resultados...'; }"),
            ],
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
             'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
             'templateResult' => new JsExpression('function(paciente) { return paciente.text; }'),
             'templateSelection' => new JsExpression('function (paciente) { return paciente.text; }'),
        ],
    ]);
    ?>
   

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
                'content' => " <div><img src='images/header_label.png' alt=''></div>
                <div>Consumos Ventanillas con demanda insatisfecha</div>",
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
    
        <?= Html::a('Volver',['consumos_ventanilla_demanda'],array('class'=>'btn btn-primary'));?>
    </div>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'showPageSummary' => true,
        'pjax'=>true,
        // 'striped'=>true,
        // 'hover'=>true,
         // 'rowOptions' => function ($model, $key, $index, $grid) {
         //        return [
         //        'id' => $model['AM_CODMON'],
         //        'style' => "cursor: pointer",
         //        'onclick' => 
         //        'alert('. $model['AM_CODMON'].');'];
         //    },
        'panel'=>['type'=>'primary', 'heading'=>'Consumos Ventanillas con demanda insatisfecha'],
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
                'filename' => 'consumos_ventanilla_demanda',
            ],
            GridView::PDF => [
                   
                    'filename' => 'consumos_ventanilla_demanda',
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
            [
                'class' => '\kartik\grid\ExpandRowColumn',
                // 'detailRowCssClass' => GridView::TYPE_DEFAULT,
                'expandOneOnly' => true,
                'allowBatchToggle' => false,
                'expandIcon'=> GridView::ICON_EXPAND,
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detailUrl' => Url::to(['reportes/detalle-droga-demanda', 
                                        'ConsumosVentanillaFiltro[periodo_inicio]'=>$searchModel->periodo_inicio,
                                        'ConsumosVentanillaFiltro[periodo_fin]'=>$searchModel->periodo_fin,
                                        'ConsumosVentanillaFiltro[programas]'=>$searchModel->programas,
                                        'ConsumosVentanillaFiltro[monodroga]'=>$searchModel->monodroga,
                                        'ConsumosVentanillaFiltro[hiscli]'=>$searchModel->hiscli,]
                                        ),
               
                'detailRowCssClass' => GridView::TYPE_DEFAULT,
            ],
            [
                
                'attribute' => 'AM_CODMON',
                'label' => 'Código',
            ],
             [
                'attribute'=>'AG_NOMBRE', 
                'label' => "Denominación",
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
                    return $widget->col(5, $p) - $widget->col(3, $p);
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
    ]); ?>
<?php Pjax::end(); }?></div>

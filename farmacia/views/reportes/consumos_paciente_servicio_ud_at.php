v<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Consumos Pacientes servicio médico U.D. y acción terapéutica';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumos-paciente-servicio-index">
 <h3><?= Html::encode($this->title) ?></h3>
<div class="consumos-paciente-servicio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['consumos_paciente_servicio_ud_at'],
        'method' => 'get',
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


    <?= $form->field($searchModel, 'servicio')->widget(Select2::classname(), [
        'data' => $searchModel->listaServicios,
        'disabled' => !$filtro,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($searchModel, 'medsol')->widget(Select2::classname(), [
        'data' => $searchModel->listaMedicos,
        'disabled' => !$filtro,
        'options' => ['placeholder' => ''],
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

     <?= $form->field($searchModel, 'accion_terapeutica')->widget(Select2::classname(), [
        'data' => $searchModel->listaAccionesTerapeuticas,
        'disabled' => !$filtro,
        'options' => ['placeholder' => ''],
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
                'content' => " <div><img src='images/header_label.png' alt=''></div>
                <div>Consumos Pacientes servicio médico U.D. y acción terapéutica</div>",
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
    
        <?= Html::a('Volver',['consumos_paciente_servicio_ud_at'],array('class'=>'btn btn-primary'));?>
    </div>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'showPageSummary' => true,
        'pjax'=>true,
        // 'striped'=>true,
        // 'hover'=>true,
        
        'panel'=>['type'=>'primary', 'heading'=>'Consumos Pacientes servicio médico U.D. y acción terapéutica'],
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
                'filename' => 'consumos_paciente_servicio_ud_at',
            ],
            GridView::PDF => [
                   
                    'filename' => 'consumos_paciente_servicio_ud_at',
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
                
                'attribute' => 'CM_HISCLI',
                'label' => 'HC',
            ],
             [
                
                'attribute' => 'nombre_paciente',
                'label' => 'Paciente',
            ],
            [
                
                'attribute' => 'supervisor_sol',
                'label' => 'Supervisor',
            ],
            [
                
                'attribute' => 'fecha',
                'label' => 'Fecha',
                'format'=>['date', 'php:d-m-Y'], 
            ],
            [
                'attribute'=>'cantidad_entregada', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Cantidad",
            ],
             [
                'attribute'=>'valor', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Valor Compra",
                'mergeHeader'=>true,
            ],
            [
                'attribute' => 'CM_SERSOL',
                'label' => 'Servicio',
                'width' => '50px',
                'value'=>function ($model, $key, $index, $widget) { 
                    return "Servicio Solicitante: ".$model['servicio_sol'];
                },
                'group' => true,
                 'groupedRow'=>true, 

            ],
            [
                'attribute' => 'CM_MEDICO',
                'label' => 'Medico',
                'width' => '50px',
                'value'=>function ($model, $key, $index, $widget) { 
                    return "Médico Solicitante: ".$model['medico_sol'];
                },
                'group' => true,
                 'groupedRow'=>true,  
                'subGroupOf' => 6,     
            ],
             [
                'attribute' => 'CM_UNIDIAG',
                'label' => 'Unidad',
                'width' => '50px',
                'value'=>function ($model, $key, $index, $widget) { 
                    return "Unidad de Diagnóstico: ".$model['unidad_sol'];
                },
                'group' => true,
                  'groupedRow'=>true,   
               
            ],
             [
                'attribute' => 'codmon',
                'label' => 'Droga',
                'width' => '50px',
                'value'=>function ($model, $key, $index, $widget) { 
                    return "Droga: ".$model['AG_NOMBRE'];
                },
                'group' => true,
                  'groupedRow'=>true,   
                  
                
            ],
           
        ],
    ]); ?>
<?php Pjax::end(); }?></div>

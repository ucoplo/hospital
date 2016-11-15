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

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Consumos Pacientes Ambulatorios de SMU';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumos-ambu_smu-index">
 <h3><?= Html::encode($this->title) ?></h3>
<div class="consumos-ambu-smu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['consumos_paciente_ambu_smu'],
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

     <?php  // Get the initial city description
    $url = \yii\helpers\Url::to(['pacientelist']);
    $pacienteNombre = empty($searchModel->hiscli) ? '' : Paciente::findOne($searchModel->hiscli)->PA_APENOM;
     
    echo $form->field($searchModel, 'hiscli')->widget(Select2::classname(), [
        'initValueText' => $pacienteNombre, // set the initial display text
        'options' => ['placeholder' => ''],
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
     
    <?= $form->field($searchModel, 'monodroga')->widget(Select2::classname(), [
        'data' => $searchModel->listaMonodrogas,
        'disabled' => !$filtro,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($searchModel, 'clases')->widget(Select2::classname(), [
        'data' => $searchModel->listaClases,
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
                'content' => " <div><img src='images/header_label.png' alt=''></div>
                <div>Consumos Pacientes Ambulatorios de SMU</div>",
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
    
        <?= Html::a('Volver',['consumos_paciente_ambu_smu'],array('class'=>'btn btn-primary'));?>
    </div>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'showPageSummary' => true,
        'pjax'=>true,
        // 'striped'=>true,
        // 'hover'=>true,
        
        'panel'=>['type'=>'primary', 'heading'=>'Consumos Pacientes Ambulatorios de SMU'],
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
                'filename' => 'consumos_paciente_ambu_smu',
            ],
            GridView::PDF => [
                   
                    'filename' => 'consumos_paciente_ambu_smu',
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
                'attribute' => 'fecha',
                'label' => 'Fecha',
                'format'=>['date', 'php:d-m-Y'], 
            ],
             [
                'attribute' => 'hora',
                'label' => 'Hora',
            ],
             [
                'attribute' => 'codmon',
                'label' => 'Código',
            ],
             [
                'attribute' => 'AG_NOMBRE',
                'label' => 'Denominación',
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
                'attribute' => 'CM_HISCLI',
                'label' => 'Paciente',
                'width' => '50px',
                'value'=>function ($model, $key, $index, $widget) { 
                    return "Paciente: ".$model['CM_HISCLI'].'-'.$model['nombre_paciente'];
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
                'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                    return [
                        'mergeColumns'=>[[0,3]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            0=>'Total ',
                           5=>GridView::F_SUM,
                        ],
                         'contentFormats'=>[      // content reformatting for each summary cell
                           
                            5=>['format'=>'number', 'decimals'=>2],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            0=>['style'=>'font-variant:small-caps'],
                           
                            5=>['style'=>'text-align:right'],
                        ],
                        // html attributes for group summary row
                        'options'=>['class'=>'success','style'=>'font-weight:bold;text-align:right;']
                    ];
                }
            ],
            
          
           
        ],
    ]); ?>
<?php Pjax::end(); }?></div>

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

$this->title = 'Consumos Pacientes Ambulatorios Valorizado';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumos-ambu_smu-index">
 
 <h3><?= Html::encode($this->title) ?></h3>

<div class="consumos-ambu-smu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['consumos_paciente_ambu_valorizado'],
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
                <div>Consumos Pacientes Ambulatorios Valorizado</div>",
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
    
        <?= Html::a('Volver',['consumos_paciente_ambu_valorizado'],array('class'=>'btn btn-primary'));?>
    </div>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'showPageSummary' => true,
        'pjax'=>true,
        // 'striped'=>true,
        // 'hover'=>true,
        
        'panel'=>['type'=>'primary', 'heading'=>'Consumos Pacientes Ambulatorios Valorizado'],
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
                'filename' => 'consumos_paciente_ambu_valorizado',
            ],
            GridView::PDF => [
                   
                    'filename' => 'consumos_paciente_ambu_valorizado',
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
                'attribute'=>'AM_FECHA', 
                'label' => 'Fecha',
                'format'=>['date', 'php:d-m-Y'], 
                 'width'=>'50px',
                  'group' => true,
                  'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                    return [
                        'mergeColumns'=>[[0,5]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            5=>'Total Fecha '.Yii::$app->formatter->asDate($model['AM_FECHA'],'php:d-m-Y'),
                          
                            6=>GridView::F_SUM,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                           
                            6=>['format'=>'number', 'decimals'=>2],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            5=>['style'=>'font-variant:small-caps'],
                           
                            6=>['style'=>'text-align:right'],
                        ],
                       'options'=>['style'=>'font-weight:bold;text-align:right;']
                    ];
                } 
            ],
             [
                'attribute'=>'AM_HISCLI', 
                'label' => "Paciente",
                 'group' => true,
                 //'groupedRow'=>true,  
                'subGroupOf' => 0,  
                'value'=>function ($model, $key, $index, $widget) { 
                    return $model['AM_HISCLI'].' - '.$model['PA_APENOM'];
                }, 
                'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                    return [
                        'mergeColumns'=>[[1,5]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            5=>'Total Paciente '.$model['PA_APENOM'],
                          
                            6=>GridView::F_SUM,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                           
                            6=>['format'=>'number', 'decimals'=>2],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            5=>['style'=>'font-variant:small-caps'],
                           
                            6=>['style'=>'text-align:right'],
                        ],
                       'options'=>['style'=>'font-weight:bold;text-align:right;']
                    ];
                } 
            ],
             
               [
                
                'attribute' => 'PR_NOMBRE',
                'label' => 'Programa',
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
                'pageSummary' => 'Total',
            ],
            [
                'attribute'=>'valor', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Valor",
                'pageSummary'=> true,
            ],
           
        ],
    ]); ?>
<?php Pjax::end(); }?></div>

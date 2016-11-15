<?php

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

$this->title = 'Consumos Pacientes Ambulatorios discriminando por OOSS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumos-ambu_ooss-index">
 
 <h3><?= Html::encode($this->title) ?></h3>

<div class="consumos-ambu-ooss-search">

    <?php $form = ActiveForm::begin([
        'action' => ['consumos_paciente_ambu_ooss'],
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

    <?= $form->field($searchModel, 'obrasocial')->widget(Select2::classname(), [
        'data' => $searchModel->listaObrasSociales,
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

    <?= $form->field($searchModel, 'clases')->widget(Select2::classname(), [
        'data' => $searchModel->listaClases,
        'disabled' => !$filtro,
        'options' => ['placeholder' => '','multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>
     
   <?= $form->field($searchModel, 'programas')->widget(Select2::classname(), [
        'data' => $searchModel->listaProgramas,
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
                <div>Consumos Pacientes Ambulatorios discriminando por OOSS</div>",
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
    
        <?= Html::a('Volver',['consumos_paciente_ambu_ooss'],array('class'=>'btn btn-primary'));?>
    </div>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'showPageSummary' => true,
        'pjax'=>true,
        // 'striped'=>true,
        // 'hover'=>true,
        
        'panel'=>['type'=>'primary', 'heading'=>'Consumos Pacientes Ambulatorios discriminando por OOSS'],
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
                'filename' => 'consumos_paciente_ambu_ooss',
            ],
            GridView::PDF => [
                   
                    'filename' => 'consumos_paciente_ambu_ooss',
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
                'attribute'=>'OB_COD', 
                'label' => "Entidad",
                'group' => true,
                  'groupedRow'=>true, 
                'value'=>function ($model, $key, $index, $widget) { 
                    return "Obra Social: ".$model['OB_COD'].' - '.$model['OB_NOM'];
                },
                 'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row',
                 'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                    return [
                        'mergeColumns'=>[[1,6]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            6=>'Total Paciente '.$model['OB_NOM'],
                          
                            7=>GridView::F_SUM,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                           
                            7=>['format'=>'number', 'decimals'=>2],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            6=>['style'=>'font-variant:small-caps'],
                           
                            7=>['style'=>'text-align:right'],
                        ],
                       'options'=>['style'=>'font-weight:bold;text-align:right;']
                    ];
                } 
            ],
            [
                'attribute'=>'AM_FECHA', 
                'label' => 'Fecha',
                'format'=>['date', 'php:d-m-Y'], 
                 'width'=>'50px',
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
                        'mergeColumns'=>[[1,6]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            5=>'Total Paciente '.$model['PA_APENOM'],
                          
                            7=>GridView::F_SUM,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                           
                            7=>['format'=>'number', 'decimals'=>2],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            5=>['style'=>'font-variant:small-caps'],
                           
                            7=>['style'=>'text-align:right'],
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
            ],
            [
                'attribute'=>'valor', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Valor",
                
            ],
           
            
            
            
           
           
        ],
    ]); ?>
<?php Pjax::end(); }?></div>

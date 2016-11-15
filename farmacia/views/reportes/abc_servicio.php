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

$this->title = 'Abc por Servicio';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="abc-servicio-index">

 <h3><?= Html::encode($this->title) ?></h3>

 
    <div class="abc-servicio-search">

            <?php $form = ActiveForm::begin([
                'action' => ['abc_servicio'],
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
            <?= $form->field($searchModel, 'deposito')->dropDownList($searchModel->listaDepositos, ['disabled'=>!$filtro,'prompt' => '']);?>
            
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

             <?= $form->field($searchModel, 'servicio')->widget(Select2::classname(), [
                'data' => $searchModel->listaServicios,
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
                'content' => " <div><img src='images/header_label.png' alt=''></div><div>ABC</div>",
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
    
        <?= Html::a('Volver',['abc'],array('class'=>'btn btn-primary'));?>
    </div>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax'=>true,
        'panel'=>['type'=>'primary', 'heading'=>'ABC'],
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
                'filename' => 'abc',
            ],
            GridView::PDF => [
                   
                    'filename' => 'abc',
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
                'attribute' => 'se_descri',
                'group' => true,
                'groupedRow'=>true,     
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row',
                'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                    return [
                        'mergeColumns'=>[[1,3]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            3=>'Total '.$model['se_descri'],
                          
                            6=>GridView::F_SUM,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                           
                            6=>['format'=>'number', 'decimals'=>2],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            3=>['style'=>'font-variant:small-caps'],
                           
                            6=>['style'=>'text-align:right'],
                        ],
                       'options'=>['style'=>'font-weight:bold;text-align:right;']
                    ];
                }
            ],
            [   
                'attribute'=>'clasifica_abc', 
                'label' => "Zonas",
                //'groupedRow'=>true,  
                'group'=>true,  
                'subGroupOf' => 0,
                'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                    return [
                        'mergeColumns'=>[[1,3]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            3=>'Total Zona ' . $model['clasifica_abc'] ,
                            6=>GridView::F_SUM,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                            6=>['format'=>'number', 'decimals'=>2],
                         
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            3=>['style'=>'font-variant:small-caps'],
                            6=>['style'=>'text-align:right'],
                          
                        ],
                        // html attributes for group summary row
                        'options'=>['style'=>'font-weight:bold;text-align:right;']
                    ];
                }
            ],
             [
                'attribute'=>'codmon', 
                'width'=>'50px',
            ],
             [
                'attribute'=>'AG_NOMBRE', 
                'width'=>'350px',
                'label' => 'Nombre Monodroga',
            ],
            
            [
                'attribute'=>'total_consumo', 
                'width'=>'100px', 
                'format'=>['decimal', 2], 
                'hAlign'=>'right', 
              
                'label' => 'Consumo',
                 
            ],
            [
                'attribute'=>'AG_PRECIO', 
                'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'100px', 
                'label' => 'Precio Unitario',
            ],
            [
                'attribute'=>'consumo_valor', 
                'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'100px', 
                'label' => 'Consumo Valorizado',
                'pageSummary'=>true
            ],
            [
                'attribute'=>'porc_abc', 
                'format'=>['decimal',2], 
                'hAlign'=>'right', 
                'width'=>'100px', 
                'label' => 'Participación Acumulada',
                
            ],
        ],
    ]); ?>
<?php Pjax::end(); }?></div>




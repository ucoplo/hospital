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

$this->title = 'Abc';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listado-index">

 <h3><?= Html::encode($this->title) ?></h3>

 
    <div class="abc-search">

            <?php $form = ActiveForm::begin([
                'action' => ['abc'],
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
            
            <?= $form->field($searchModel, 'clases')->widget(Select2::classname(), [
                'data' => $searchModel->listaClases,
                'disabled' => !$filtro,
                'options' => ['placeholder' => '','multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>

            <div class="row">
                <div class="col-md-4">
                     <?= $form->field($searchModel, 'activos')->radioList(array('T' => 'Si', 'F' => 'No', '' => 'Todos')); ?>
                </div>
            </div>
            
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
                'attribute'=>'clasifica_abc', 
                'label' => "Zonas",
                //'groupedRow'=>true,  
                'group'=>true,  
                'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                    return [
                        'mergeColumns'=>[[1,3]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            4=>'Total Zona ' . $model->clasifica_abc ,
                            5=>GridView::F_SUM,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                            5=>['format'=>'number', 'decimals'=>2],
                         
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            4=>['style'=>'font-variant:small-caps'],
                            5=>['style'=>'text-align:right'],
                          
                        ],
                        // html attributes for group summary row
                        'options'=>['style'=>'font-weight:bold;']
                    ];
                }
            ],
             [
                'attribute'=>'MD_CODMON', 
                'width'=>'50px',
            ],
             [
                'attribute'=>'monodroga.AG_NOMBRE', 
                'width'=>'350px',
                'label' => 'Nombre Monodroga',
            ],
            
            [
                'attribute'=>'consumo', 
                'width'=>'100px', 
                'label' => 'Consumo',
                 
            ],
            [
                'attribute'=>'monodroga.AG_PRECIO', 
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
                'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'100px', 
                'label' => 'Participación Acumulada',
            ],
        ],
    ]); ?>
<?php Pjax::end(); }?></div>




<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\widgets\DetailView;


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Consumos por Servicio y Clase';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumos-servicio-clase-index"><?php
    if ($filtro){?>
 <h3><?= Html::encode($this->title) ?></h3>
<div class="consumos-servicio-clase-search">

    <?php $form = ActiveForm::begin([
        'action' => ['consumo_por_servicio_clase'],
        'method' => 'post',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-10',
                'error' => '',
                'hint' => '',
            ],
        ],
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
                    <label class="control-label col-sm-2">Fecha</label>
            
                <div class="col-md-10 ">
                    <?= DatePicker::widget([
                            'type'=>DatePicker::TYPE_RANGE,
                            'model' => $searchModel,
                            'attribute' => 'fecha_inicio',
                            'attribute2' => 'fecha_fin',
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
            <br>

    
             <?= $form->field($searchModel, 'clases')->widget(Select2::classname(), [
                'data' => $searchModel->listaClases,
                'disabled' => !$filtro,
                'options' => ['placeholder' => '','multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>

            
             <div class="row">
                <div class="col-md-2 col-md-offset-10 text-right"><?php
                if($filtro) {
                    echo Html::submitButton('Buscar...', ['class' => 'btn btn-primary']);
                }?>
                </div>
            </div>

    <?php ActiveForm::end(); ?>

</div>
    
   

<?php }
   if (!$filtro){ 
        
        $pdfHeader = [
            'L' => [
                'content' => '',
                 'font-size' => 15,
                'color' => '#377333'
            ],
            'C' => [
                'content' => " <div><img src='images/header_label.png' alt=''></div><div>Consumos por Servicio y Clase</div>",
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
                'content' => 'Depósito Central '.date("d-m-Y h:i"),
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
            <?= Html::a('Nueva Búsqueda',['consumo_por_servicio_clase'], ['class' => 'btn btn-success']);?>
            <?= Html::a('Modificar Filtro', 'javascript:history.go(-1)', ['class' => 'btn btn-primary']);?>
        </div>
        <h3>Datos del Filtro</h3>
        <?php $filtro_consulta=DetailView::widget([
            'model' => $searchModel,
            'attributes' => [
                 [
                    'class' =>  yii\grid\DataColumn::className(), // this line is optional
                    'attribute' => 'deposito',
                    'format' => 'text',
                    'value' => $searchModel->deposito_descripcion,
                ],
                'fecha_inicio',
                'fecha_fin',
                [
                    'class' =>  yii\grid\DataColumn::className(), // this line is optional
                    'attribute' => 'clases',
                    'format' => 'text',
                    'value' => $searchModel->clases_descripcion,
                ],
               
            ],
        ]);
        echo $filtro_consulta; ?>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'showPageSummary' => true,

        'pjax'=>true,
         'striped'=>true,
         'hover'=>true,
        
        'panel'=>['type'=>'primary', 'heading'=>'Consumos por Servicio'],
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
                'filename' => 'consumos_servicio_clase',
            ],
            GridView::PDF => [
                   
                    'filename' => 'consumos_servicio_clase',
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
                        'contentBefore' => $filtro_consulta,
                    ]
                ],
            ],
        'columns' => [
             ['class'=>'kartik\grid\SerialColumn'],
             [
                'attribute' => 'se_descri',
                'group' => true,
                'groupedRow'=>true,     
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row',
                'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                    return [
                        'mergeColumns'=>[[2,5]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            4=>'Total '.$model['se_descri'],
                          
                            7=>GridView::F_SUM,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                           
                            7=>['format'=>'number', 'decimals'=>2],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            4=>['style'=>'font-variant:small-caps'],
                           
                            7=>['style'=>'text-align:right'],
                        ],
                       'options'=>['style'=>'font-weight:bold;text-align:right;']
                    ];
                }
            ],
            [
                'attribute' => 'CL_NOM',
                'label' => 'Clase',
                //'groupedRow'=>true,     
                'group'=>true,  // enable grouping
                'subGroupOf'=>1 ,// supplier column index is the parent group
                'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                    return [
                        'mergeColumns'=>[[2,5]], // columns to merge in summary
                        'content'=>[             // content to show in each summary cell
                            4=>'Total '.$model['CL_NOM'],
                          
                            7=>GridView::F_SUM,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                           
                            7=>['format'=>'number', 'decimals'=>2],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            3=>['style'=>'font-variant:small-caps'],
                           
                            7=>['style'=>'text-align:right'],
                        ],
                       'options'=>['class'=>'success','style'=>'font-weight:bold;text-align:right;']
                    ];
                }
            ],
            [
                //'attribute'=>'rMCODMON.AG_NOMBRE', 
                'attribute' => 'codart',
                'label' => 'Código',
                'width' => '50px',
               
            ],
             [
                'attribute'=>'AG_NOMBRE', 
                'label' => "Denominación",
                
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
                'header'=>'Valor',
                'value'=>function ($model, $key, $index, $widget) { 
                    $p = compact('model', 'key', 'index');
                    return $widget->col(5, $p) * $widget->col(6, $p);
                },
                'mergeHeader'=>true,
                'width'=>'150px',
                'hAlign'=>'right',
                'format'=>['decimal', 2],
               
            ],
          
           
        ],
    ]); ?>
<?php Pjax::end(); }?></div>

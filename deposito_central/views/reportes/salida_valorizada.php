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

$this->title = 'Salida Valorizada';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salida_valorizada-index"><?php
    if ($filtro){?>
         <h3><?= Html::encode($this->title) ?></h3>
        <div class="salida_valorizada-search">

            <?php $form = ActiveForm::begin([
                'action' => ['salida_valorizada'],
                'fieldConfig' => [
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-2',
                        'wrapper' => 'col-sm-10',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
                'method' => 'post',
                'layout' => 'horizontal'
            ]); ?>
            
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
                <div class="col-md-2 col-md-offset-10 text-right"><?php
                if($filtro) {
                    echo Html::submitButton('Buscar...', ['class' => 'btn btn-primary']);
                }?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

<?php 
    }
   if (!$filtro){ 
        $pdfHeader = [
            'L' => [
                'content' => '',
                 'font-size' => 15,
                'color' => '#377333'
            ],
            'C' => [
                'content' => " <div><img src='images/header_label.png' alt=''></div><div>Salida Valorizada</div>",
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
            <?= Html::a('Nueva Búsqueda',['salida_valorizada'], ['class' => 'btn btn-success']);?>
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
                'pjax'=>true,
                'panel'=>['type'=>'primary', 'heading'=>'Salida Valorizada'],
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
                        'filename' => 'salida_valorizada',
                    ],
                    GridView::PDF => [
                           
                            'filename' => 'salida_valorizada',
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
                     [
                        'attribute'=>'AG_CODIGO', 
                        'width'=>'50px',
                        'label'=> 'Código',
                    ],
                     [
                        'attribute'=>'AG_NOMBRE', 
                        'width'=>'350px',
                        'label' => 'Nombre Artículo',
                    ],
                     [
                        'attribute'=>'salida_anual', 
                        'format'=>['decimal', 2], 
                        'hAlign'=>'right', 
                        'width'=>'15%', 
                        'label' => 'Salida',
                    ],
                    [
                        'attribute'=>'AG_STACT', 
                        'format'=>['decimal', 2], 
                        'hAlign'=>'right', 
                        'width'=>'100px', 
                        'label' => 'Existencia',
                    ], 
                    [
                        'attribute'=>'AG_PRECIO', 
                        'format'=>['decimal', 2], 
                        'hAlign'=>'right', 
                        'width'=>'150px', 
                        'label' => 'Costo unitario',
                    ],
                    
                    [
                        'attribute'=>'salida_valorizada', 
                        'format'=>['decimal', 2], 
                        'hAlign'=>'right', 
                        'width'=>'15%', 
                        'label' => 'Salida valorizada',
                    ],
                    [
                        'attribute'=>'existencia_valorizada', 
                        'format'=>['decimal', 2], 
                        'hAlign'=>'right', 
                        'width'=>'15%', 
                        'label' => 'Existencia valorizada',
                    ],
                   
                ],
            ]); ?>
        <?php Pjax::end(); }?>
    </div>

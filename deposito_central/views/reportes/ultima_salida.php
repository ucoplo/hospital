<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
use yii\widgets\DetailView;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Última Salida';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ultima-salida-index"><?php
    if ($filtro){?>

 <h3><?= Html::encode($this->title) ?></h3>
<div class="ultima-salida-searh">

    <?php $form = ActiveForm::begin([
        'action' => ['ultima_salida'],
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
    
    <?= $form->field($searchModel, 'deposito')->dropDownList($searchModel->listaDepositos, ['disabled'=>!$filtro,'prompt' => 'Seleccione Depósito']);?>

    <?= $form->field($searchModel, 'fecha_hasta')->widget(DateControl::classname(), [
            'type'=>DateControl::FORMAT_DATE,
            
            'ajaxConversion'=>false,
            'options' => [
                'disabled'=>!$filtro,
                'removeButton' => false,
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]
        ]);
    ?>

    <div class="row">
        <div class="col-md-3">
          <?= $form->field($searchModel, 'nombre1',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'nombre2',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'nombre3',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'nombre4',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])->textInput(['disabled'=>!$filtro]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
          <?= $form->field($searchModel, 'limite1',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'limite2',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'limite3',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'limite4',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])->textInput(['disabled'=>!$filtro]) ?>
        </div>
    </div>

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
                'content' => " <div><img src='images/header_label.png' alt=''></div><div>Artículos según Última Salida</div>",
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
            <?= Html::a('Nueva Búsqueda',['cardex_articulos'], ['class' => 'btn btn-success']);?>
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
                'fecha_hasta:date',
                
            ],
        ]);
        echo $filtro_consulta; ?>

        <?php Pjax::begin(); ?>    <?php
        echo GridView::widget([
                'dataProvider' => $dataProvider,
                'pjax'=>true,
                'panel'=>['type'=>'primary', 'heading'=>'Artículos según Última Salida'],
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
                        'filename' => 'articulos_ultima_salida',
                    ],
                    GridView::PDF => [
                           
                            'filename' => 'articulos_ultima_salida',
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
                    ],
                     [
                        'attribute'=>'AG_NOMBRE', 
                        'width'=>'350px',
                    ],
                    [
                        'attribute'=>'clasifica', 
                        'width'=>'50px',
                        'groupedRow'=>true,  
                        'group'=>true,
                        'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                        'groupEvenCssClass'=>'kv-grouped-row',
                           
                    ],
                    [
                        'attribute'=>'AG_ULTSAL', 
                         'format'=>['date', 'php:d-m-Y'],
                        'hAlign'=>'center', 
                        'width'=>'100px', 
                    ],
                    [
                        'attribute'=>'dias_salida', 
                        'width'=>'50px',
                        'label' => 'Días',
                        'hAlign'=>'center',
                    ],
                    
                   
                ],
            ]); ?>
        <?php Pjax::end(); }?></div>

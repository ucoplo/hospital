<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Última Salida';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ultima-salida-index">
 <h3><?= Html::encode($this->title) ?></h3>
<div class="ultima-salida-searh">

    <?php $form = ActiveForm::begin([
        'action' => ['ultima_salida'],
        'method' => 'get',
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
          <?= $form->field($searchModel, 'nombre1')->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'nombre2')->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'nombre3')->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'nombre4')->textInput(['disabled'=>!$filtro]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
          <?= $form->field($searchModel, 'limite1')->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'limite2')->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'limite3')->textInput(['disabled'=>!$filtro]) ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($searchModel, 'limite4')->textInput(['disabled'=>!$filtro]) ?>
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
    
        <?= Html::a('Volver',['ultima_salida'],array('class'=>'btn btn-primary'));?>
    </div>
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

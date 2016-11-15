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

$this->title = 'Artículos ordenados por indice de rotación';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indices-index">

 <h3><?= Html::encode($this->title) ?></h3>

 
    <div class="indices-search">

            <?php $form = ActiveForm::begin([
                'action' => ['indices'],
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
                               <div>Artículos ordenados por indice de rotación</div>",
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
    
        <?= Html::a('Volver',['indices'],array('class'=>'btn btn-primary'));?>
    </div>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax'=>true,
        'panel'=>['type'=>'primary', 'heading'=>'Artículos ordenados por indice de rotación'],
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
                'filename' => 'indices',
            ],
            GridView::PDF => [
                   
                    'filename' => 'indices',
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
                //'attribute'=>'rMCODMON.AG_NOMBRE', 
                'attribute' => 'codmon',
                'label' => 'Código',
               
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
                'attribute'=>'indice', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Indice",
            ],
          
           
        ],
    ]); ?>
<?php Pjax::end(); }?></div>




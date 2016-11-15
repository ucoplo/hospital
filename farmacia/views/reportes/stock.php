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

$this->title = 'Stock';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-index">
 <h3><?= Html::encode($this->title) ?></h3>
<div class="stock-search">

    <?php $form = ActiveForm::begin([
        'action' => ['stock'],
        'method' => 'get',
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
        <div class="col-md-4">
            <?= $form->field($searchModel, 'vademecum')->radioList(array('S' => 'Si', 'N' => 'No', '' => 'Todos')); ?>
        </div>
        <div class="col-md-4">
             <?= $form->field($searchModel, 'activo')->radioList(array('T' => 'Si', 'F' => 'No', '' => 'Todos')); ?>
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
                'content' => " <div><img src='images/header_label.png' alt=''></div><div>Stock</div>",
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
    
        <?= Html::a('Volver',['stock'],array('class'=>'btn btn-primary'));?>
    </div>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax'=>true,
        'panel'=>['type'=>'primary', 'heading'=>'Stock'],
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
                'filename' => 'stock',
            ],
            GridView::PDF => [
                   
                    'filename' => 'stock',
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
                'label' => 'Nombre Monodroga',
            ],
            
            [
                'attribute'=>'clase', 
                'width'=>'100px', 
                'label' => 'Nombre Clase',
                 'value'=>function ($model, $key, $index, $widget) { 
                    return $model->AG_CODCLA.' - '.$model->clase->CL_NOM;
                },
            ],
            [
                'attribute'=>'AG_STACT', 
                'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'100px', 
                'label' => 'Stock',
            ],
        ],
    ]); ?>
<?php Pjax::end(); }?></div>

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

$this->title = 'Techos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="techos-index">
 <h3><?= Html::encode($this->title) ?></h3>
<div class="techos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['techos'],
        'method' => 'get',
    ]); ?>
    
    <?= $form->field($searchModel, 'deposito')->dropDownList($searchModel->listaDepositos, ['disabled'=>!$filtro,'prompt' => 'Seleccione Depósito']);?>

      
     <?= $form->field($searchModel, 'clases')->widget(Select2::classname(), [
        'data' => $searchModel->listaClases,
        'disabled' => !$filtro,
        'options' => ['placeholder' => '','multiple' => true],
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
                               <div>Techos</div>",
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
    
        <?= Html::a('Volver',['techos'],array('class'=>'btn btn-primary'));?>
    </div>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'showPageSummary' => true,
        'pjax'=>true,
        // 'striped'=>true,
        // 'hover'=>true,
        
        'panel'=>['type'=>'primary', 'heading'=>'Techos'],
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
                'filename' => 'techos',
            ],
            GridView::PDF => [
                   
                    'filename' => 'techos',
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
                
                'attribute' => 'AG_CODIGO',
                'label' => "Código",
               
            ],
             [
                'attribute'=>'AG_NOMBRE', 
                'label' => "Denominación",
            ],
            [
                'attribute'=>'TM_CANTID', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Cantidad Máxima",
            ],
          
           
        ],
    ]); ?>
<?php Pjax::end(); }?></div>

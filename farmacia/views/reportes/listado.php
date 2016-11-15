<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ucfirst($datos_listado['titulo']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listado-index">

 <h3><?= Html::encode($this->title) ?></h3>

<?php  
    echo $this->render($datos_listado['titulo'].'_search', ['model' => $searchModel,'filtro'=> $datos_listado['filtro']]);
    if (! $datos_listado['filtro']){   
   
        $pdfHeader = [
            'L' => [
                'content' => 'Hospital Municipal ',
                'font-size' => 8,
                'color' => '#333333'
            ],
            'C' => [
                'content' => "<div style='font-size=30;'>".ucfirst($datos_listado['titulo'])."</div>",
                'font-size' => 8,
                'color' => '#333333'
            ],
            'R' => [
                'content' => date("d-m-Y h:i"),
                'font-size' => 8,
                'color' => '#333333'
            ]
        ];
        $pdfFooter = [
            'L' => [
                'content' => '',
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
     

      Pjax::begin();?>

    
    <div class="form-group">
    
        <?= Html::a('Volver',[$datos_listado['titulo']],array('class'=>'btn btn-primary'));?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        
        'pjax'=>true,
      'showPageSummary'=>true,
        'panel'=>['type'=>'primary', 'heading'=>ucfirst($datos_listado['titulo'])],
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
                'filename' => $datos_listado['titulo'],
            ],
            GridView::PDF => [
                    'filename' => $datos_listado['titulo'],
                    'config' => [
                        'mode' => 'c',
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
        
        'columns' => $datos_listado['columnas'],
    ]); 
    Pjax::end(); }?></div>

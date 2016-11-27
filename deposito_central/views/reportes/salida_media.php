<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use deposito_central\models\ArticGral;
use yii\helpers\Url;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Salida media';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salida-media-index"><?php
    if ($filtro){?>

 <h3><?= Html::encode($this->title) ?></h3>
<div class="salida-media-search">

    <?php $form = ActiveForm::begin([
        'action' => ['salida_media'],
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

   <?php  $url_busqueda_articulos = \yii\helpers\Url::to(['reportes/buscar-articulos']);
             
                    $articuloNombre = empty($searchModel->articulo) ? '' : "[$searchModel->articulo]".ArticGral::findOne($searchModel->articulo)->AG_NOMBRE;
            ?>
             <?= $form->field($searchModel, 'articulo')->widget(Select2::classname(), [
                'initValueText' => $articuloNombre, // set the initial display text
                'options' => ['placeholder' => ''],
                //'data' => (!empty($searchModel->articulo))?[ "{$searchModel->articulo}" => "[{$searchModel->articulo}] ".$searchModel->articulo]:[],

                'pluginOptions' => [
                    'ajax' => [
                        'url' => $url_busqueda_articulos,
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {
                          return {q:params.term};
                        }')
                    ],
                    'allowClear' => true,
                    'enableEmpty' => true,
                    'minimumInputLength' => 1,
                    'language' => 'es',
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(articulo) { return articulo.text; }'),
                    'templateSelection' => new JsExpression('function (articulo) { return articulo.text; }'),
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
                [
                    'class' =>  yii\grid\DataColumn::className(), // this line is optional
                    'attribute' => 'articulo',
                    'format' => 'text',
                    'value' => $searchModel->articulo_descripcion,
                ],
               
                
            ],
        ]);
        echo $filtro_consulta; 

$datos = [];
$labels = [];
foreach ($salida_media as $key => $value) {
    $datos[] = floatval($value['total_consumo']);

    $labels[] = Yii::$app->formatter->asDate($value['fecha'],'php:d-m-Y'); 
    
}
$descri_articulo = "[$searchModel->articulo]".ArticGral::findOne(['AG_CODIGO'=>$searchModel->articulo,'AG_DEPOSITO'=>$searchModel->deposito])->AG_NOMBRE;

echo Highcharts::widget([
     'scripts' => [
         //'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)
         'modules/exporting', // adds Exporting button/menu to chart
         //'themes/grid'        // applies global 'grid' theme to all charts
        ],
   'options' => [
      'chart' => array(
                    'defaultSeriesType' => 'areaspline',
                    'style' => array(
                        'fontFamily' => 'Verdana, Arial, Helvetica, sans-serif',
                    ),
                ),
      'title' => ['text' => 'Salida Media'],
      'credits' => ['enabled' => false],

      'xAxis' => [
         'categories' => $labels
      ],
      'yAxis' => [
         'title' => ['text' => 'Consumo']
      ],
      'series' => [
         ['name' => $descri_articulo, 'data' => $datos ]
         
      ]
   ]
]);

 }?>

</div>

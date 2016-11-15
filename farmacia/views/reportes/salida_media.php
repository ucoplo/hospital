<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use miloschuman\highcharts\Highcharts;
use farmacia\models\ArticGral;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Salida media';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salida-media-index">
 <h3><?= Html::encode($this->title) ?></h3>
<div class="salida-media-search">

    <?php $form = ActiveForm::begin([
        'action' => ['salida_media'],
        'method' => 'get',
    ]); ?>
    
    <?= $form->field($searchModel, 'deposito')->dropDownList($searchModel->listaDepositos, ['disabled'=>!$filtro,'prompt' => 'Seleccione Depósito']);?>

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
?>
<div class="form-group">
    
        <?= Html::a('Volver',['salida_media'],array('class'=>'btn btn-primary'));?>
    </div>
<?php

$datos = [];
$labels = [];
foreach ($salida_media as $key => $value) {
    $datos[] = floatval($value['total_consumo']);

    $labels[] = Yii::$app->formatter->asDate($value['fecha'],'php:d-m-Y'); 
    
}
$descri_monodroga = ArticGral::findOne($searchModel->monodroga)->AG_NOMBRE;

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
         ['name' => $descri_monodroga, 'data' => $datos ]
         
      ]
   ]
]);

 }?>

</div>

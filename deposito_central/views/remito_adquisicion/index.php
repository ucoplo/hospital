<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Remito_AdquisicionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Adquisiciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remito--adquisicion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
         <?= Html::a('Nueva Adquisición', ['seleccion_orden_compra'], ['class' => 'btn btn-success popupModal']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            

            'RA_NUM',
            'RA_FECHA:date',
            'RA_HORA',
                     
            'RA_CONCEP:ntext',
            // 'RA_TIPMOV',
            // 'RA_DEPOSITO',
            // 'RA_REMDEP',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
<?php
    yii\bootstrap\Modal::begin([
        'header' => '<center><h2>Tipo de Adquisición</h2></center>',
        'id' =>'modal',
        'size' => 'modal-lg',
    ]);
    echo '<div class="row text-center">';
    echo '<div class="col-md-6">'.Html::a('Sin Orden de Compra', ['create_adquisicion'], ['class' => 'btn btn-success btn-lg'])."</div>";  
    echo '<div class="col-md-6">'.Html::a('Con Orden de Compra', ['seleccion_orden_compra'], ['class' => 'btn btn-success btn-lg'])."</div>";  
    echo '</div>';
    yii\bootstrap\Modal::end();

    $this->registerJs("$(function() {
       $('.popupModal').click(function(e) {
         e.preventDefault();
         $('#modal').modal('show');
       });
    });");

?>
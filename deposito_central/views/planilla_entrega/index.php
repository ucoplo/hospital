<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Planilla_entregaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planillas de entrega';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumo-medicamentos-granel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Planilla', ['seleccion_servicio'], ['class' => 'btn btn-success popupModal']) ?>

        <?= Html::a('Nueva Planilla sin Pedido', ['create_sin_pedido'], ['class' => 'btn btn-success']) ?>
  
    </p>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                'id' => $model->PE_NROREM,
                'style' =>
                    ($model->PE_PROCESADO) ? 'background-color: #85C485;' : '',
                ];
            },
        'columns' => [
           

            'PE_NROREM',
             [
                'attribute' => 'PE_SERSOL',
                'value' => 'servicio.SE_DESCRI',
                
            ],
            'PE_FECHA:date',
            'PE_HORA',
            
             [
                'attribute' => 'PE_PROCESADO',
                'value' => function($model){
                            if ($model->PE_PROCESADO){
                                return $model->operador->LE_APENOM;
                            }else{
                                return "";
                            }
                },
            ],
            //'PE_CODOPE',
            // 'PE_DEPOSITO',
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}',
            ],
            //Si no estaba procesado se podia elimianr o modificar
            /*['class' => 'yii\grid\ActionColumn',
             'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return $model->PE_PROCESADO ? false : true;
                     },
                     'delete' => function ($model, $key, $index) {
                        return $model->PE_PROCESADO ? false : true;
                     },
                ]
            ],*/

        ],
    ]); ?>
<?php Pjax::end(); ?></div>
<?php
    yii\bootstrap\Modal::begin([
        'header' => '<center><h2>Tipo de Pedidos</h2></center>',
        'id' =>'modal',
        'size' => 'modal-lg',
    ]);
    echo '<div class="row text-center">';
    echo '<div class="col-md-6">'.Html::a('Pedidos Insumos Salas', ['seleccion_servicio'], ['class' => 'btn btn-success btn-lg'])."</div>";  
    echo '<div class="col-md-6">'.Html::a('Pedidos de Farmacia', ['seleccion_pedido_farmacia'], ['class' => 'btn btn-success btn-lg'])."</div>";  
    echo '</div>';
    yii\bootstrap\Modal::end();

    $this->registerJs("$(function() {
       $('.popupModal').click(function(e) {
         e.preventDefault();
         $('#modal').modal('show');
       });
    });");

?>
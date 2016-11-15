<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Productos_kairos */

$this->title = $model->codigo.' - '.$model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Productos Kairos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="productos-kairos-view">

    <h2><?= Html::encode($this->title) ?></h2>

   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'codigo',
            'descripcion',
            'laboratorio_descripcion',
            // 'origen',
            // 'psicofarmaco',
            // 'codigo_venta',
            // 'estupefaciente',
            // 'estado',
        ],
    ]) ?>
    <div id="grid_presentaciones">
        <?= GridView::widget([
            'dataProvider' => $model->presentaciones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                
                'descripcion',
               
                [
                
                'label' => 'Drogas',
                'attribute'=> 'monodroga', 
                ] ,
                [

                'label' => 'Precio',
                'attribute'=> 'precio_publico', 
                'headerOptions' => ['width' => '150'],
                'value' =>    function($model){return  (isset($model['precio_publico']))?$model['precio_publico']:'';},   
                ] 
               
                 
                
                  
                
            ],
        ]); ?>
    </div>
</div>

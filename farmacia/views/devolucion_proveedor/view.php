<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use farmacia\models\Labo;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_proveedor */

$this->title = "Devolución a Proveedor Nro. ".str_pad($model->DE_NROREM, 6, '0', STR_PAD_LEFT);
$this->params['breadcrumbs'][] = ['label' => 'Devoluciones a Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-proveedor-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="text-right">
    <?= Html::a('<i class="fa glyphicon glyphicon-print"></i> Imprimir',['report', 'id' => $model->DE_NROREM],
        [
         'class'=>'btn text-right',
         'target'=> '_blank',
         'data-toggle'=>'tooltip',
         'title'=>'Imprimir']); ?> 

     </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'DE_NROREM',
            'DE_FECHA:date',
            'DE_HORA',
            
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DE_PROVE',
                'format' => 'text',
                'value' => $model->descripcion_proveedor(),
                
            ],
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'deposito',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
        ],          
    ]) ?>

    <div id="grid_devolucion_renglones">
        <?= GridView::widget([
            'dataProvider' => $model->renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                'DP_CODMON',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->dPCODMON->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                ],   
                'DP_FECVTO:date', 
                'DP_CANTID',   
                  
                
            ],
        ]); ?>
                
             
              
        </div>

</div>

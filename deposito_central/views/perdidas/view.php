<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Perdidas */

$this->title = "Remito Pérdida Nro. ".str_pad($model->DP_NROREM, 6, '0', STR_PAD_LEFT);
$this->params['breadcrumbs'][] = ['label' => 'Pérdidas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perdidas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-right">
    <?= Html::a('<i class="fa glyphicon glyphicon-print"></i> Imprimir',['report', 'id' => $model->DP_NROREM],
        [
         'class'=>'btn text-right',
         'target'=> '_blank',
         'data-toggle'=>'tooltip',
         'title'=>'Imprimir']); ?> 

     </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'DP_NROREM',
            'DP_FECHA:date',
            'DP_HORA',
            
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DP_MOTIVO',
                'format' => 'text',
                'value' => $model->motivo->MP_NOM,
                
            ],
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DP_DEPOSITO',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
        ],          
    ]) ?>

    <div id="grid_perdida_renglones">
        <?= GridView::widget([
            'dataProvider' => $model->renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                'DR_CODART',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->articulo->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                ],   
                'DR_FECVTO:date', 
                'DR_CANTID',   
                  
                
            ],
        ]); ?>
                
             
              
    </div>


   

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Perdidas */

$this->title = "Remito Pérdida Nro. ".str_pad($model->PE_NROREM, 6, '0', STR_PAD_LEFT);
$this->params['breadcrumbs'][] = ['label' => 'Pérdidas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perdidas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-right">
    <?= Html::a('<i class="fa glyphicon glyphicon-print"></i> Imprimir',['report', 'id' => $model->PE_NROREM],
        [
         'class'=>'btn text-right',
         'target'=> '_blank',
         'data-toggle'=>'tooltip',
         'title'=>'Imprimir']); ?> 

     </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'PE_NROREM',
            'PE_FECHA:date',
            'PE_HORA',
            
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_MOTIVO',
                'format' => 'text',
                'value' => $model->motivo->MP_NOM,
                
            ],
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_DEPOSITO',
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
                'PF_CODMON',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->monodroga->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                ],   
                'PF_FECVTO:date', 
                'PF_CANTID',   
                  
                
            ],
        ]); ?>
                
             
              
    </div>


   

</div>

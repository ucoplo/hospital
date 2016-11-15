<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_salas_granel */

$this->title = 'Devolución Nro. '.$model->DE_NRODEVOL;
$this->params['breadcrumbs'][] = ['label' => 'Devoluciones de Salas Granel', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-salas-granel-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p class="text-right">
        <?= Html::a('<i class="fa glyphicon glyphicon-print"></i> Imprimir',['report', 'id' => $model->DE_NRODEVOL],
            [
             'class'=>'btn text-right',
             'target'=> '_blank',
             'data-toggle'=>'tooltip',
             'title'=>'Imprimir']); ?> 

     </p>
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'DE_NRODEVOL',
            'DE_FECHA:date',
            'DE_HORA',
            'DE_NUMREMOR',
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DE_SERSOL',
                'format' => 'text',
                'value' => $model->servicio->SE_DESCRI,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DE_CODOPE',
                'format' => 'text',
                'value' => $model->operador->LE_APENOM,
            ],
           
           
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DE_ENFERM',
                'format' => 'text',
                'value' => $model->enfermero->LE_APENOM,
            ],

             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DE_DEPOSITO',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
           
            
        ],
    ]) ?>

    
    <div id="grid_devolucion_granel_renglones">
        <?= GridView::widget([
            'dataProvider' => $model->renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                'DF_CODMON',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($renglon){return  $renglon->monod->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                ],   
                'DF_FECVTO:date', 
                'DF_CANTID',   
                
                  
                
            ],
        ]); ?>
    </div>


    

</div>

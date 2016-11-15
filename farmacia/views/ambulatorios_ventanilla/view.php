<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Ambulatorios_ventanilla */

$this->title = "Vale de Ventanillla Nro. ".$model->AM_NUMVALE;
$this->params['breadcrumbs'][] = ['label' => 'Ambulatorios Ventanillas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ambulatorios-ventanilla-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-right">
        <?= Html::a('<i class="fa glyphicon glyphicon-print"></i> Imprimir',['report', 'id' => $model->AM_NUMVALE],
            [
             'class'=>'btn text-right',
             'target'=> '_blank',
             'data-toggle'=>'tooltip',
             'title'=>'Imprimir']); ?> 

     </p>
     
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AM_HISCLI',
                'format' => 'text',
                'value' => $model->AM_HISCLI.' - '.$model->paciente->PA_APENOM,
            ],
            
         
            'AM_FECHA:date',
            'AM_HORA',
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AM_PROG',
                'format' => 'text',
                'value' => $model->programa->PR_NOMBRE,
            ],
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AM_ENTIDER',
                'format' => 'text',
                'value' => $model->entidad->ED_DETALLE,
            ],
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AM_MEDICO',
                'format' => 'text',
                'value' => $model->medico->LE_APENOM,
            ],
                        
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AM_DEPOSITO',
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
                'AM_CODMON',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->monodroga->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                ],   
                'AM_FECVTO:date', 
                'AM_CANTPED',   
                'AM_CANTENT',   
                  
                
            ],
        ]); ?>
    </div>


</div>

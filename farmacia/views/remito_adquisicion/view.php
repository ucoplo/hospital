<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_Adquisicion */


$this->title = str_pad($model->RE_NUM, 6, '0', STR_PAD_LEFT).' - '.$model->RE_CONCEP;
$this->params['breadcrumbs'][] = ['label' => 'Remitos de Adquisición', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remito--adquisicion-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="text-right">
    <?= Html::a('<i class="fa glyphicon glyphicon-print"></i> Imprimir',['report', 'id' => $model->RE_NUM],
        [
         'class'=>'btn ',
         'target'=> '_blank',
         'data-toggle'=>'tooltip',
         'title'=>'Imprimir']); ?> 

    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'RE_NUM',
                'format' => 'text',
                'value' => str_pad($model->RE_NUM, 6, '0', STR_PAD_LEFT),
            ],
            'RE_FECHA:date',
            'RE_HORA',
            
            'RE_CONCEP:ntext',
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'RE_DEPOSITO',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
           [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'RE_REMDEP',
                'label' => $model->deposito_externo('label'),
                'format' => 'text',
                'value' => $model->deposito_externo('value'),
            ],
        ],
    ]) ?>

     <div id="grid_adquisicion_renglones">
        <?= GridView::widget([
            'dataProvider' => $renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                'RM_CODMON',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return   $model->monodroga->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                     'contentOptions' => ['style' => 'width:500px;'],
                ],   
                'RM_FECVTO:date', 
                'RM_CANTID',   
                  
                
            ],
        ]); ?>
                
             
              
    </div>
    
    

</div>

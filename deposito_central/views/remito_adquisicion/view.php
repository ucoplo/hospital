<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_Adquisicion */


$this->title = str_pad($model->RA_NUM, 6, '0', STR_PAD_LEFT).' - '.$model->RA_CONCEP;
$this->params['breadcrumbs'][] = ['label' => 'Remitos de Adquisición', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remito--adquisicion-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="text-right">
    <?= Html::a('<i class="fa glyphicon glyphicon-print"></i> Imprimir',['report', 'id' => $model->RA_NUM],
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
                'attribute' => 'RA_NUM',
                'format' => 'text',
                'value' => str_pad($model->RA_NUM, 6, '0', STR_PAD_LEFT),
            ],
            'RA_FECHA:date',
            'RA_HORA',
            
            'RA_CONCEP:ntext',
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'RA_DEPOSITO',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'RA_OCNRO',
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
                'AR_CODART',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return   $model->monodroga->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                     'contentOptions' => ['style' => 'width:500px;'],
                ],   
                'AR_FECVTO:date', 
                'AR_CANTID',   
                  
                
            ],
        ]); ?>
                
             
              
    </div>
    
    

</div>

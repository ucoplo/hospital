<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
//use kartik\detail\DetailView;
//use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Pedentre */

$this->title = str_pad($model->PE_NROPED, 6, '0', STR_PAD_LEFT)." - ".$model->PE_REFERENCIA;
$this->params['breadcrumbs'][] = ['label' => 'Pedidos de Reposición', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedentre-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->PE_NROPED], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro de eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'PE_FECHA:date',
            
            'PE_REFERENCIA',
            'PE_CLASE',
            //'PE_PROCESADO',
           
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $model->pedido_renglones,
        'emptyText' => '',
        'summary'=>"",
        'columns' => [
            

            [
                'attribute' => 'PE_NRORENG',
                'label' => 'Renglón',
            ],      
            [
                'attribute' => 'PE_CODMON',
                'label' => 'Código',
                'class' =>  yii\grid\DataColumn::className(), 
            ],   
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'descripcion',
                'format' => 'text',
                'value' => function ($model){return $model->pECODMON->AG_NOMBRE;},
            ],
           
              
            [
                'attribute' => 'PE_CANTPED',
                'label' => 'Cantidad pedida',
            ],    
            
            
         
          
        ],
    ]); ?>

</div>

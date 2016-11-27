<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Pedido_adquisicion */

$this->title = "Pedido Nro ".$model->PE_NUM;
$this->params['breadcrumbs'][] = ['label' => 'Pedidos de Adquisición', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedido-adquisicion-view">

    <h1><?= Html::encode($this->title) ?></h1>

   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'PE_NUM',
            'PE_FECHA:date',
            'PE_HORA',
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_DEPOSITO',
                'format' => 'text',
                'value' => Yii::$app->formatter->asCurrency($model->PE_COSTO),
            ],
            'PE_REFERENCIA:ntext',
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_DEPOSITO',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
            'PE_ARTDES',
            'PE_ARTHAS',
            'PE_CLASES',
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_EXISACT',
                'format' => 'text',
                'value' => ($model->PE_EXISACT)?'Si':'No',
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_PEDPEND',
                'format' => 'text',
                'value' => ($model->PE_EXISACT)?'Si':'No',
            ],
            'PE_PONDHIS',
            'PE_PONDPUN',
            'PE_CLASABC',
            'PE_DIASABC',
            'PE_DIASPREVIS',
            'PE_DIASDEMORA',
        ],
    ]) ?>

     <?= GridView::widget([
        'dataProvider' => $model->renglones,
        'emptyText' => '',
        'summary'=>"",
        'columns' => [
            

            [
                'attribute' => 'PE_NRORENG',
                'label' => 'Renglón',
            ],      
            [
                'attribute' => 'PE_CODART',
                'label' => 'Código',
                'class' =>  yii\grid\DataColumn::className(), 
            ],   
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'descripcion',
                'format' => 'text',
                'value' => function ($model){return $model->articulo->AG_NOMBRE;},
            ],
           
              
            [
                'attribute' => 'PE_CANTPED',
                'label' => 'Cantidad pedida',
            ],    
            
            
         
          
        ],
    ]); ?>

</div>

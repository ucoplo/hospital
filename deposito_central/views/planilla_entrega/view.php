<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use deposito_central\assets\Planilla_entregaAsset;


Planilla_entregaAsset::register($this);


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Planilla_entrega */
if ($model->PE_PROCESADO){
    $label_boton = "Reimprimir";
    $id_boton = 'btn_reimprimir';
}
else{
    $label_boton = "Entregar";
    $id_boton = 'btn_entregar';
}


$this->title = "Planilla de entrega Nro. ".$model->PE_NROREM;
$this->params['breadcrumbs'][] = ['label' => 'Planillas de entrega', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="planilla_entrega-granel-view">

    <h1><?= Html::encode($this->title) ?></h1>

     <p class="text-right">
        <?= Html::a('<i class="fa glyphicon glyphicon-print"></i> '.$label_boton,['report', 'id' => $model->PE_NROREM],
            [
             'class'=>'btn text-right',
             'id' => $id_boton,
             'target'=> '_blank',
             'data-toggle'=>'tooltip',
             'title'=>$label_boton]); ?> 

         </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'PE_NROREM',
            'PE_FECHA:date',
            'PE_HORA',
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_SERSOL',
                'format' => 'text',
                'value' => $model->servicio->SE_DESCRI,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_CODOPE',
                'format' => 'text',
                'value' => $model->operador->LE_APENOM,
            ],
           
           
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_ENFERM',
                'format' => 'text',
                'value' => $model->enfermero->LE_APENOM,
            ],
           
            
        ],
    ]) ?>

    
    <?= Html::hiddenInput('nro_remito', $model->PE_NROREM,['id'=>'nro_remito']); ?>

    <div id="grid_planilla_entrega_renglones">
        <?= GridView::widget([
            'dataProvider' => $model->renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
               
                [
                    'attribute' => 'PR_CODART',
                    'label' => 'Código',
                ],  
                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->articulo->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                ],   
                'PR_FECVTO:date', 
                'PR_CANTID',   
                
                  
                
            ],
        ]); ?>
    </div>

</div>

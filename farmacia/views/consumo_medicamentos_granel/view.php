<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use farmacia\assets\Planilla_GranelAsset;
use kartik\dialog\Dialog;

echo Dialog::widget();

Planilla_GranelAsset::register($this);


/* @var $this yii\web\View */
/* @var $model farmacia\models\Consumo_medicamentos_granel */
if ($model->CM_PROCESADO){
    $label_boton = "Reimprimir";
    $id_boton = 'btn_reimprimir';
}
else{
    $label_boton = "Entregar";
    $id_boton = 'btn_entregar';
}


$this->title = "Planilla a granel Nro. ".$model->CM_NROREM;
$this->params['breadcrumbs'][] = ['label' => 'Planillas de Retiro a Granel', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumo-medicamentos-granel-view">

    <h1><?= Html::encode($this->title) ?></h1>

     <p class="text-right">
        <?= Html::a('<i class="fa glyphicon glyphicon-print"></i> '.$label_boton,['report', 'id' => $model->CM_NROREM],
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
            'CM_NROREM',
            'CM_FECHA:date',
            'CM_HORA',
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'CM_SERSOL',
                'format' => 'text',
                'value' => $model->servicio->SE_DESCRI,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'CM_CODOPE',
                'format' => 'text',
                'value' => $model->operador->LE_APENOM,
            ],
           
           
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'CM_ENFERM',
                'format' => 'text',
                'value' => $model->enfermero->LE_APENOM,
            ],
           
            
        ],
    ]) ?>

    
    <?= Html::hiddenInput('nro_remito', $model->CM_NROREM,['id'=>'nro_remito']); ?>

    <div id="grid_planilla_granel_renglones">
        <?= GridView::widget([
            'dataProvider' => $model->renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
               
                [
                    'attribute' => 'PF_CODMON',
                    'label' => 'Código',
                ],  
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

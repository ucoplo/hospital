<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use farmacia\assets\Vale_FarmaciaAsset;
use kartik\dialog\Dialog;

echo Dialog::widget();

Vale_FarmaciaAsset::register($this);

/* @var $this yii\web\View */
/* @var $model farmacia\models\Consumo_medicamentos_pacientes */
if ($model->CM_PROCESADO){
    $label_boton = "Reimprimir";
    $id_boton = 'btn_reimprimir';
}
else{
    $label_boton = "Entregar";
    $id_boton = 'btn_entregar';
}

if ($model->CM_CONDPAC=='I'){
    $tipo = "Internados";
    $vista = '_form_internados';
}
else{
    $tipo = "Ambulatorios";
    $vista = '_form_ambulatorios';
}


$this->title = "Remito Nro. ". $model->CM_NROREM ." - Vale de Farmacia ".$tipo." Nro. ".$model->CM_NROVAL;
$this->params['breadcrumbs'][] = ['label' => 'Consumo Medicamentos Pacientes '.$tipo, 'url' => ['index','condpac'=>$model->CM_CONDPAC]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumo-medicamentos-pacientes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!$model->CM_PROCESADO){?>
    <?php }?>
    <div class="text-right">
        <?php if ($model->CM_PROCESADO){?>
            <div class="btn btn-link">
                <?= Html::a('<i class="fa glyphicon glyphicon-print"></i> '.$label_boton.' Vale',['imprimir', 'id' => $model->CM_NROVAL],
                    [
                     'class'=>'btn text-right',
                     'target'=> '_blank',
                     'data-toggle'=>'tooltip',
                     'title'=>'Entregar']); ?> 
            </div>
        <?php }?>   
         <div class="btn btn-link">
            <?= Html::a('<i class="fa glyphicon glyphicon-print"></i> '.$label_boton.' Remito',['imprimir_remito', 'id' => $model->CM_NROREM,'condpac'=>$model->CM_CONDPAC],
                [
                 'class'=>'btn text-right',
                 'id' => $id_boton,
                 'target'=> '_blank',
                 'data-toggle'=>'tooltip',
                 'title'=>$label_boton]); ?> 
        </div>
    </div>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           
            'CM_HISCLI',
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'CM_HISCLI',
                'format' => 'text',
                'label' => "Apellido y Nombres",
                'value' => $model->paciente->PA_APENOM,
            ],
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
                'attribute' => 'CM_UNIDIAG',
                'format' => 'text',
                'value' => $model->cMUNIDIAG->SE_DESCRI,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'CM_CONDPAC',
                'format' => 'text',
                'value' => $model->condicion_paciente,
            ],
                      
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'CM_SUPERV',
                'format' => 'text',
                'value' => $model->supervisor->LE_APENOM,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'CM_MEDICO',
                'format' => 'text',
                'value' => $model->medico->LE_APENOM,
            ],
            
        ],
    ]) ?>

    <?= Html::hiddenInput('nro_remito', $model->CM_NROREM,['id'=>'nro_remito']); ?>
    <?= Html::hiddenInput('cond_pac', $model->CM_CONDPAC,['id'=>'cond_pac']); ?>

    <div id="grid_devolucion_renglones">
        <?= GridView::widget([
            'dataProvider' => $model->renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                'VA_CODMON',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->monodroga->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                ],   
                'VA_FECVTO:date', 
                'VA_CANTID',   
                
                  
                
            ],
        ]); ?>
    </div>

</div>

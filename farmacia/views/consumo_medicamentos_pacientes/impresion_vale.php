<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Consumo_medicamentos_pacientes */
if ($model->CM_CONDPAC=='I'){
    $tipo = "Internados";
    $vista = '_form_internados';
}
else{
    $tipo = "Ambulatorios";
    $vista = '_form_ambulatorios';
}

?>
<div class="consumo-medicamentos-pacientes-view">
      <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">  Vale de Farmacia <?=$tipo?> Nro. <?= Html::encode(str_pad($model->CM_NROVAL, 6, '0', STR_PAD_LEFT)) ?></h2>
    
  
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'CM_NROREM',
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
            
            'CM_NROVAL',
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
       <table style="width:100%;">
      <tr>
        <td style="width:60%;">
        </td>
        <td style="text-align:center;">
            <?=  (file_exists(Yii::$app->params['local_path']['path_firmas'].'/'.$model->CM_CODOPE).'.png')
                ?'<img src="'.Yii::$app->params['local_path']['path_firmas'].'/'.$model->CM_CODOPE.'.png" style="max-height:5em;max-width: 20em">'
                :'';?>
        </td>
      </tr>
      <tr>
        <td style="width:60%;">       
        </td>
        <td style="text-align:center;">
            <p ><?= $model->operador->LE_APENOM ?></p>
        </td>
      </tr>
    </table>
</div>

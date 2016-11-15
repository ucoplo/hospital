<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use deposito_central\assets\Planilla_entregaAsset;
use kartik\dialog\Dialog;

echo Dialog::widget();

Planilla_entregaAsset::register($this);
/* @var $this yii\web\View */
/* @var $model deposito_central\models\Consumo_medicamentos_pacientes */

?>
<div class="consumo-medicamentos-pacientes-view">
      <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">  Planilla de entrega Nro. <?= Html::encode(str_pad($model->PE_NROREM, 6, '0', STR_PAD_LEFT)) ?></h2>
    
  
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

    <div id="grid_devolucion_renglones">
        <?= GridView::widget([
            'dataProvider' => $model->renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                'PR_CODART',

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
    <table style="width:100%;">
      <tr>
        <td style="width:60%;">
        </td>
        <td style="text-align:center;">
            <?=  (file_exists(Yii::$app->params['local_path']['path_firmas'].'/'.$model->PE_CODOPE).'.png')
                ?'<img src="'.Yii::$app->params['local_path']['path_firmas'].'/'.$model->PE_CODOPE.'.png" style="max-height:5em;max-width: 20em">'
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

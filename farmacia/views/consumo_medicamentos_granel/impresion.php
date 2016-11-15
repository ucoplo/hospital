<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Consumo_medicamentos_pacientes */

?>
<div class="consumo-medicamentos-pacientes-view">
      <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">  Planilla a Granel Nro. <?= Html::encode(str_pad($model->CM_NROREM, 6, '0', STR_PAD_LEFT)) ?></h2>
    
  
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

    <div id="grid_devolucion_renglones">
        <?= GridView::widget([
            'dataProvider' => $model->renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                'PF_CODMON',

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

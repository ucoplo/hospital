<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use farmacia\models\Labo;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_proveedor */

$this->title = str_pad($model->DE_NRODEVOL, 6, '0', STR_PAD_LEFT);

?>
<div class="ambultarios-ventanilla-view">
     <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">Devolución Sala de Paciente Nro. <?= Html::encode(str_pad($model->DE_NRODEVOL, 6, '0', STR_PAD_LEFT)) ?></h2>

   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'DE_NRODEVOL',
            'DE_FECHA:date',
            'DE_HORA',
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DE_HISCLI',
                'format' => 'text',
                'value' => $model->DE_HISCLI.' - '.$model->paciente->PA_APENOM,
            ],
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DE_SERSOL',
                'format' => 'text',
                'value' => $model->servicio->SE_DESCRI,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DE_CODOPE',
                'format' => 'text',
                'value' => $model->operador->LE_APENOM,
            ],
           
           
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DE_ENFERM',
                'format' => 'text',
                'value' => $model->enfermero->LE_APENOM,
            ],

             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DE_DEPOSITO',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
           
            
        ],
    ]) ?>

    
    <div id="grid_devolucion_granel_renglones">
        <?= GridView::widget([
            'dataProvider' => $model->renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                'DV_CODMON',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->monod->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                ],   
                'DV_FECVTO:date', 
                'DV_CANTID',   
                
                  
                
            ],
        ]); ?>
    </div>
    <table style="width:100%;">
      <tr>
        <td style="width:60%;">
        </td>
        <td style="text-align:center;">
            <?=  (file_exists(Yii::$app->params['local_path']['path_firmas'].'/'.$model->DE_CODOPE).'.png')
                ?'<img src="'.Yii::$app->params['local_path']['path_firmas'].'/'.$model->DE_CODOPE.'.png" style="max-height:5em;max-width: 20em">'
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

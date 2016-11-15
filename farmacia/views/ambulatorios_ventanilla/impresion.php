<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use farmacia\models\Labo;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_proveedor */

$this->title = str_pad($model->AM_NUMVALE, 6, '0', STR_PAD_LEFT);

?>
<div class="ambultarios-ventanilla-view">
     <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">Vale de Ventanillla Nro. <?= Html::encode(str_pad($model->AM_NUMVALE, 6, '0', STR_PAD_LEFT)) ?></h2>

   
   <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'AM_HISCLI',
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AM_HISCLI',
                'format' => 'text',
                'label' => "Apellido y Nombres",
                'value' => $model->paciente->PA_APENOM,
            ],
            
         
            'AM_FECHA:date',
            'AM_HORA',
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AM_PROG',
                'format' => 'text',
                'value' => $model->programa->PR_NOMBRE,
            ],
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AM_ENTIDER',
                'format' => 'text',
                'value' => $model->entidad->ED_DETALLE,
            ],
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AM_MEDICO',
                'format' => 'text',
                'value' => $model->medico->LE_APENOM,
            ],
                        
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AM_DEPOSITO',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
            
        ],
    ]) ?>

    <div id="grid_devolucion_renglones">
        <?= GridView::widget([
            'dataProvider' => $model->renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                'AM_CODMON',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->monodroga->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                ],   
                'AM_FECVTO:date', 
                'AM_CANTPED',   
                'AM_CANTENT',   
                  
                
            ],
        ]); ?>
    </div>
    <table style="width:100%;">
      <tr>
        <td style="width:60%;">
        </td>
        <td >
            <?=  (file_exists(Yii::$app->params['local_path']['path_firmas'].'/'.$model->AM_FARMACEUTICO).'.png')
                ?'<img src="'.Yii::$app->params['local_path']['path_firmas'].'/'.$model->AM_FARMACEUTICO.'.png" style="max-height:5em;max-width: 20em">'
                :'';?>
        </td>
      </tr>
      <tr>
        <td style="width:60%;">       
        </td>
        <td style="text-align:center;">
            <p ><?= $model->farmaceutico->LE_APENOM ?></p>
        </td>
      </tr>
    </table>


</div>

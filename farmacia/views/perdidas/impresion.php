<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_proveedor */

$this->title = str_pad($model->PE_NROREM, 6, '0', STR_PAD_LEFT);

?>
<div class="devolucion-proveedor-view">
     <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">Remito Pérdida Nro. <?= Html::encode(str_pad($model->PE_NROREM, 6, '0', STR_PAD_LEFT)) ?></h2>

   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'PE_NROREM',
            'PE_FECHA:date',
            'PE_HORA',
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_MOTIVO',
                'format' => 'text',
                'value' => $model->motivo->MP_NOM,
                
            ],
           
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_DEPOSITO',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
        ],          
    ]) ?>

    <div id="grid_perdida_renglones">
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

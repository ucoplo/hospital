<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use farmacia\models\Labo;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_proveedor */

$this->title = str_pad($model->DE_NROREM, 6, '0', STR_PAD_LEFT);

?>
<div class="devolucion-proveedor-view">
     <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">Devolución a Proveedor <?= Html::encode(str_pad($model->DE_NROREM, 6, '0', STR_PAD_LEFT)) ?></h2>

   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'DE_NROREM',
            'DE_FECHA:date',
            'DE_HORA',
            
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DE_PROVE',
                'format' => 'text',
                'value' => $model->descripcion_proveedor(),
                
            ],
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'deposito',
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
                'DP_CODMON',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->dPCODMON->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                ],   
                'DP_FECVTO:date', 
                'DP_CANTID',   
                  
                
            ],
        ]); ?>
    </div>
    <table style="width:100%;">
      <tr>
        <td style="width:60%;">
        </td>
        <td >
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

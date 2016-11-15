<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Devolucion_proveedor */

$this->title = str_pad($model->DP_NROREM, 6, '0', STR_PAD_LEFT);

?>
<div class="devolucion-proveedor-view">
     <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">Remito Pérdida Nro. <?= Html::encode(str_pad($model->DP_NROREM, 6, '0', STR_PAD_LEFT)) ?></h2>

   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'DP_NROREM',
            'DP_FECHA:date',
            'DP_HORA',
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DP_MOTIVO',
                'format' => 'text',
                'value' => $model->motivo->MP_NOM,
                
            ],
           
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DP_DEPOSITO',
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
                'DR_CODART',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->articulo->AG_NOMBRE;},     
                    'label' => 'Descripción del artículo',
                ],   
                'DR_FECVTO:date', 
                'DR_CANTID',   
                  
                
            ],
        ]); ?>
                
             
              
        </div>

    <table style="width:100%;">
      <tr>
        <td style="width:60%;">
        </td>
        <td style="text-align:center;">
            <?=  (file_exists(Yii::$app->params['local_path']['path_firmas'].'/'.$model->DP_CODOPE).'.png')
                ?'<img src="'.Yii::$app->params['local_path']['path_firmas'].'/'.$model->DP_CODOPE.'.png" style="max-height:5em;max-width: 20em">'
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

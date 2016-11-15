<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Devolucion_proveedor */

$this->title = str_pad($model->DD_NROREM, 6, '0', STR_PAD_LEFT);

?>
<div class="devolucion-proveedor-view">
     <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">Devolución a Proveedor <?= Html::encode(str_pad($model->DD_NROREM, 6, '0', STR_PAD_LEFT)) ?></h2>

   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'DD_NROREM',
            'DD_FECHA:date',
            'DD_HORA',
            
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DD_PROVE',
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
                'DP_CODART',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->articulo->AG_NOMBRE;},     
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
            <?=  (file_exists(Yii::$app->params['local_path']['path_firmas'].'/'.$model->DD_CODOPE).'.png')
                ?'<img src="'.Yii::$app->params['local_path']['path_firmas'].'/'.$model->DD_CODOPE.'.png" style="max-height:5em;max-width: 20em">'
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

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_Adquisicion */

$this->title = $model->RA_NUM.' - '.$model->RA_CONCEP;

?>
<div class="remito--adquisicion-view">
    <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">Remito de Adquisición <?= Html::encode(str_pad($model->RA_NUM, 6, '0', STR_PAD_LEFT)) ?></h2>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'RA_NUM',
                'format' => 'text',
                'value' => str_pad($model->RA_NUM, 6, '0', STR_PAD_LEFT),
            ],
            'RA_FECHA:date',
            'RA_HORA',
            
            'RA_CONCEP:ntext',
             
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'RA_DEPOSITO',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'RA_REMDEP',
                'label' => $model->deposito_externo('label'),
                'format' => 'text',
                'value' => $model->deposito_externo('value'),
            ],
           
        ],
    ]) ?>

     <div id="grid_adquisicion_renglones">
        <?= GridView::widget([
            'dataProvider' => $renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                'AR_CODART',
                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return   $model->articulo->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                    'contentOptions' => ['style' => 'width:400px;'],
                ],   
                'AR_FECVTO:date', 
                'AR_CANTID',   
                  
                
            ],
        ]); ?>
    </div>
    <table style="width:100%;">
      <tr>
        <td style="width:60%;">
        </td>
        <td style="text-align:center;">
            <?=  (file_exists(Yii::$app->params['local_path']['path_firmas'].'/'.$model->RA_CODOPE).'.png')
                ?'<img src="'.Yii::$app->params['local_path']['path_firmas'].'/'.$model->RA_CODOPE.'.png" style="max-height:5em;max-width: 20em">'
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

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_Adquisicion */

$this->title = $model->RE_NUM.' - '.$model->RE_CONCEP;

?>
<div class="remito--adquisicion-view">
    <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">Remito de Adquisición <?= Html::encode(str_pad($model->RE_NUM, 6, '0', STR_PAD_LEFT)) ?></h2>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'RE_NUM',
                'format' => 'text',
                'value' => str_pad($model->RE_NUM, 6, '0', STR_PAD_LEFT),
            ],
            'RE_FECHA:date',
            'RE_HORA',
            
            'RE_CONCEP:ntext',
             
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'RE_DEPOSITO',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'RE_REMDEP',
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
                'RM_CODMON',
                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return   $model->monodroga->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                    'contentOptions' => ['style' => 'width:400px;'],
                ],   
                'RM_FECVTO:date', 
                'RM_CANTID',   
                  
                
            ],
        ]); ?>
    </div>
    <table style="width:100%;">
      <tr>
        <td style="width:60%;">
        </td>
        <td style="text-align:center;">
            <?=  (file_exists(Yii::$app->params['local_path']['path_firmas'].'/'.$model->RE_CODOPE).'.png')
                ?'<img src="'.Yii::$app->params['local_path']['path_firmas'].'/'.$model->RE_CODOPE.'.png" style="max-height:5em;max-width: 20em">'
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

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Consumo_medicamentos_pacientes */
if ($model->CM_CONDPAC=='I'){
    $tipo = "Internados";
    $vista = '_form_internados';
}
else{
    $tipo = "Ambulatorios";
    $vista = '_form_ambulatorios';
}

?>
<div class="consumo-medicamentos-pacientes-view">
      <?= Html::img('images/header_label.png');?>
    <h2 class="text-center">  Remito <?=$tipo?> Nro. <?= Html::encode(str_pad($model->CM_NROREM, 6, '0', STR_PAD_LEFT)) ?></h2>
    
   
    <div id="grid_remito_renglones">
        <?= GridView::widget([
            'dataProvider' => $model->renglones,
            'emptyText' => '',
            'summary'=>"",
            'columns' => [
                'VA_CODMON',

                [
                    'attribute' => 'descripcion',
                    'value' =>    function($model){return  $model->monodroga->AG_NOMBRE;},     
                    'label' => 'Descripción del medicamento',
                ],   
                'VA_FECVTO:date', 
                'VA_CANTID',   
                
                  
                
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

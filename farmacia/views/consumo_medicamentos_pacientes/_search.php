<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Consumo_medicamentos_pacientesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="consumo-medicamentos-pacientes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index', 'condpac' => $condpac],
        'method' => 'get',
    ]); ?>

     <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'CM_NROREM') ?>
        </div>
         <div class="col-md-3">
            
             <div class="form-group" style="margin-top:25px;">
                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary ']) ?>
                
            </div>
        </div>
    </div>

   

    <?php ActiveForm::end(); ?>

</div>

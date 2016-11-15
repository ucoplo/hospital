<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Techo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="techo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'TM_CODSERV')->dropDownList($model->listaServicio, ['prompt' => 'Seleccione Servicio' ]);?>

    <?= $form->field($model, 'TM_DEPOSITO')->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito' ]);?>

     <?= $form->field($model, 'TM_CODMON')->dropDownList($model->listaMonodroga, ['prompt' => 'Seleccione Monodroga' ]);?>

     <?= $form->field($model, 'TM_CANTID')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Volver',['index'],array('class'=>'btn btn-primary'));?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

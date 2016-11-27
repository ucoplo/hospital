<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Pedido_reposicion_farmacia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pedido-reposicion-farmacia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'PE_FECHA')->textInput() ?>

    <?= $form->field($model, 'PE_HORA')->textInput() ?>

    <?= $form->field($model, 'PE_SERSOL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PE_DEPOSITO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PE_REFERENCIA')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'PE_CLASE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PE_SUPERV')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PE_PROCESADO')->dropDownList([ 'F' => 'F', 'T' => 'T', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\OrdenCompra */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orden-compra-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'OC_NRO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'OC_PROVEED')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'OC_FECHA')->textInput() ?>

    <?= $form->field($model, 'OC_FINALIZADA')->textInput() ?>

    <?= $form->field($model, 'OC_PEDADQ')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

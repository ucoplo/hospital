<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_Adquisicion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="remito--adquisicion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'AR_FECHA')->textInput() ?>

    <?= $form->field($model, 'AR_HORA')->textInput() ?>

    <?= $form->field($model, 'AR_CODOPE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AR_CONCEP')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'AR_TIPMOV')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AR_DEPOSITO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AR_REMDEP')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

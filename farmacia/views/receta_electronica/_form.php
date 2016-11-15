<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Receta_electronica */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="receta-electronica-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'RE_HISCLI')->textInput() ?>

    <?= $form->field($model, 'RE_FECINI')->textInput() ?>

    <?= $form->field($model, 'RE_FECFIN')->textInput() ?>

    <?= $form->field($model, 'RE_MEDICO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RE_NOTA')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

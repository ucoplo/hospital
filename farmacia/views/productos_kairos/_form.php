<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Productos_kairos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="productos-kairos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'laboratorio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'origen')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'psicofarmaco')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_venta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estupefaciente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

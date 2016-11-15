<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_deposito */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="remito-deposito-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'RS_CODEP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RS_FECHA')->textInput() ?>

    <?= $form->field($model, 'RS_HORA')->textInput() ?>

    <?= $form->field($model, 'RS_CODOPE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RS_NUMPED')->textInput() ?>

    <?= $form->field($model, 'RS_SERSOL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RS_IMPORT')->dropDownList([ 'F' => 'F', 'T' => 'T', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Via */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="via-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'VI_CODIGO')->textInput(['maxlength' => true,'readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'VI_DESCRI')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Volver',['index'],array('class'=>'btn btn-primary'));?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

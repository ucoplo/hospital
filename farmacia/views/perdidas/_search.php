<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\PerdidasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="perdidas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'PE_NROREM') ?>

    <?= $form->field($model, 'PE_FECHA') ?>

    <?= $form->field($model, 'PE_HORA') ?>

    <?= $form->field($model, 'PE_MOTIVO') ?>

    <?= $form->field($model, 'PE_CODOPE') ?>

    <?php // echo $form->field($model, 'PE_DEPOSITO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

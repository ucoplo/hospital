<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\AlarmaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alarma-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'AL_CODMON') ?>

    <?= $form->field($model, 'AL_DEPOSITO') ?>

    <?= $form->field($model, 'AL_MIN') ?>

    <?= $form->field($model, 'AL_MAX') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

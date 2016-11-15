<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\PerdidasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="perdidas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'DP_NROREM') ?>

    <?= $form->field($model, 'DP_FECHA') ?>

    <?= $form->field($model, 'DP_HORA') ?>

    <?= $form->field($model, 'DP_MOTIVO') ?>

    <?= $form->field($model, 'DP_CODOPE') ?>

    <?php // echo $form->field($model, 'DP_DEPOSITO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

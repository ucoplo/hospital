<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_AdquisicionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="remito--adquisicion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'RE_NUM') ?>

    <?= $form->field($model, 'RE_FECHA') ?>

    <?= $form->field($model, 'RE_HORA') ?>

    <?= $form->field($model, 'RE_CODOPE') ?>

    <?= $form->field($model, 'RE_CONCEP') ?>

    <?php // echo $form->field($model, 'RE_TIPMOV') ?>

    <?php // echo $form->field($model, 'RE_DEPOSITO') ?>

    <?php // echo $form->field($model, 'RE_REMDEP') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

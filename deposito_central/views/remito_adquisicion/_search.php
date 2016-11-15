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

    <?= $form->field($model, 'RA_NUM') ?>

    <?= $form->field($model, 'RA_FECHA') ?>

    <?= $form->field($model, 'RA_HORA') ?>

    <?= $form->field($model, 'RA_CODOPE') ?>

    <?= $form->field($model, 'RA_CONCEP') ?>

    <?php // echo $form->field($model, 'RA_TIPMOV') ?>

    <?php // echo $form->field($model, 'RA_DEPOSITO') ?>

    <?php // echo $form->field($model, 'RA_REMDEP') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

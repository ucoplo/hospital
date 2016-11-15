<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Ambulatorios_ventanillaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ambulatorios-ventanilla-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'AM_HISCLI') ?>

    <?= $form->field($model, 'AM_NUMVALE') ?>

    <?= $form->field($model, 'AM_FECHA') ?>

    <?= $form->field($model, 'AM_HORA') ?>

    <?= $form->field($model, 'AM_PROG') ?>

    <?php // echo $form->field($model, 'AM_ENTIDER') ?>

    <?php // echo $form->field($model, 'AM_MEDICO') ?>

    <?php // echo $form->field($model, 'AM_DEPOSITO') ?>

    <?php // echo $form->field($model, 'AM_FARMACEUTICO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

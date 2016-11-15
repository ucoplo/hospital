<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Movimientos_diariosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="movimientos-diarios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'MD_FECHA') ?>

    <?= $form->field($model, 'MD_CODMOV') ?>

    <?= $form->field($model, 'MD_CANT') ?>

    <?= $form->field($model, 'MD_FECVEN') ?>

    <?php // echo $form->field($model, 'MD_CODMON') ?>

    <?php // echo $form->field($model, 'MD_DEPOSITO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

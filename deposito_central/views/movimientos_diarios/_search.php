<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Movimientos_diariosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="movimientos-diarios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'DM_FECHA') ?>

    <?= $form->field($model, 'DM_CODMOV') ?>

    <?= $form->field($model, 'DM_CANT') ?>

    <?= $form->field($model, 'DM_FECVTO') ?>

    <?php // echo $form->field($model, 'DM_CODART') ?>

    <?php // echo $form->field($model, 'DM_DEPOSITO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

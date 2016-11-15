<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_depositoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="remito-deposito-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'RS_CODEP') ?>

    <?= $form->field($model, 'RS_NROREM') ?>

    <?= $form->field($model, 'RS_FECHA') ?>

    <?= $form->field($model, 'RS_HORA') ?>

    <?= $form->field($model, 'RS_CODOPE') ?>

    <?php // echo $form->field($model, 'RS_NUMPED') ?>

    <?php // echo $form->field($model, 'RS_SERSOL') ?>

    <?php // echo $form->field($model, 'RS_IMPORT') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

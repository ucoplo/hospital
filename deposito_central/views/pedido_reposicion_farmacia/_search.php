<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Pedido_reposicion_farmaciaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pedido-reposicion-farmacia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'PE_NROPED') ?>

    <?= $form->field($model, 'PE_FECHA') ?>

    <?= $form->field($model, 'PE_HORA') ?>

    <?= $form->field($model, 'PE_SERSOL') ?>

    <?= $form->field($model, 'PE_DEPOSITO') ?>

    <?php // echo $form->field($model, 'PE_REFERENCIA') ?>

    <?php // echo $form->field($model, 'PE_CLASE') ?>

    <?php // echo $form->field($model, 'PE_SUPERV') ?>

    <?php // echo $form->field($model, 'PE_PROCESADO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

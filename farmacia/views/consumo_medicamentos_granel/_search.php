<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Consumo_medicamentos_granelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="consumo-medicamentos-granel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'CM_NROREM') ?>

    <?= $form->field($model, 'CM_FECHA') ?>

    <?= $form->field($model, 'CM_HORA') ?>

    <?= $form->field($model, 'CM_SERSOL') ?>

    <?= $form->field($model, 'CM_ENFERM') ?>

    <?php // echo $form->field($model, 'CM_CODOPE') ?>

    <?php // echo $form->field($model, 'CM_DEPOSITO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

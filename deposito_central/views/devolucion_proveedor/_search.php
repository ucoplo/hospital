<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Devolucion_proveedorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="devolucion-proveedor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'DD_NROREM') ?>

    <?= $form->field($model, 'DD_FECHA') ?>

    <?= $form->field($model, 'DD_HORA') ?>

    <?= $form->field($model, 'DD_PROVE') ?>

    <?= $form->field($model, 'DD_CODOPE') ?>

    <?php // echo $form->field($model, 'DD_DEPOSITO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

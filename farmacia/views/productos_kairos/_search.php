<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Productos_kairosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="productos-kairos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'codigo') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'laboratorio') ?>

    <?= $form->field($model, 'origen') ?>

    <?= $form->field($model, 'psicofarmaco') ?>

    <?php // echo $form->field($model, 'codigo_venta') ?>

    <?php // echo $form->field($model, 'estupefaciente') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

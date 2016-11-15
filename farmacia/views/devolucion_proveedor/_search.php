<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_proveedorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="devolucion-proveedor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'DE_NROREM') ?>

    <?= $form->field($model, 'DE_FECHA') ?>

    <?= $form->field($model, 'DE_HORA') ?>

    <?= $form->field($model, 'DE_PROVE') ?>

    <?= $form->field($model, 'DE_CODOPE') ?>

    <?php // echo $form->field($model, 'DE_DEPOSITO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

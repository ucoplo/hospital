<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\ServicioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servicio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'SE_CODIGO') ?>

    <?= $form->field($model, 'SE_DESCRI') ?>

    <?= $form->field($model, 'SE_TPOSER') ?>

    <?= $form->field($model, 'SE_CCOSTO') ?>

    <?= $form->field($model, 'SE_SALA') ?>

    <?php // echo $form->field($model, 'SE_AREA') ?>

    <?php // echo $form->field($model, 'SE_INFO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

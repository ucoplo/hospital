<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\DrogaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="droga-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'DR_CODIGO') ?>

    <?= $form->field($model, 'DR_DESCRI') ?>

    <?= $form->field($model, 'DR_CLASE') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

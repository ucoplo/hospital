<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\ProveedoresSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="proveedores-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'PR_CODIGO') ?>

    <?= $form->field($model, 'PR_RAZONSOC') ?>

    <?= $form->field($model, 'PR_CONTACTO') ?>

    <?= $form->field($model, 'PR_TELEF') ?>

    <?= $form->field($model, 'PR_EMAIL') ?>

    <?php // echo $form->field($model, 'PR_CODRAFAM') ?>

    <?php // echo $form->field($model, 'PR_OBS') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

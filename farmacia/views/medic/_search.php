<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\MedicSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="medic-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ME_CODIGO') ?>

    <?= $form->field($model, 'ME_NOMCOM') ?>

    <?= $form->field($model, 'ME_CODKAI') ?>

    <?= $form->field($model, 'ME_CODRAF') ?>

    <?= $form->field($model, 'ME_KAIBAR') ?>

    <?php // echo $form->field($model, 'ME_KAITRO') ?>

    <?php // echo $form->field($model, 'ME_CODMON') ?>

    <?php // echo $form->field($model, 'ME_CODLAB') ?>

    <?php // echo $form->field($model, 'ME_PRES') ?>

    <?php // echo $form->field($model, 'ME_FRACCQ') ?>

    <?php // echo $form->field($model, 'ME_VALVEN') ?>

    <?php // echo $form->field($model, 'ME_ULTCOM') ?>

    <?php // echo $form->field($model, 'ME_VALCOM') ?>

    <?php // echo $form->field($model, 'ME_ULTSAL') ?>

    <?php // echo $form->field($model, 'ME_STMIN') ?>

    <?php // echo $form->field($model, 'ME_STMAX') ?>

    <?php // echo $form->field($model, 'ME_RUBRO') ?>

    <?php // echo $form->field($model, 'ME_UNIENV') ?>

    <?php // echo $form->field($model, 'ME_DEPOSITO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

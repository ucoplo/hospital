<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Receta_electronicaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="receta-electronica-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'RE_NRORECETA') ?>

    <?= $form->field($model, 'RE_HISCLI') ?>

    <?= $form->field($model, 'RE_FECINI') ?>

    <?= $form->field($model, 'RE_FECFIN') ?>

    <?= $form->field($model, 'RE_MEDICO') ?>

    <?php // echo $form->field($model, 'RE_NOTA') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

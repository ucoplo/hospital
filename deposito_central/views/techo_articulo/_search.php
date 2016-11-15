<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Techo_articuloSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="techo-articulo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'TA_CODSERV') ?>

    <?= $form->field($model, 'TA_DEPOSITO') ?>

    <?= $form->field($model, 'TA_CODART') ?>

    <?= $form->field($model, 'TA_CANTID') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

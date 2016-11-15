<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model farmacia\models\TechoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="techo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    
    <?= $form->field($model, 'TM_CODSERV') ?>

    <?= $form->field($model, 'TM_DEPOSITO') ?>

    <?= $form->field($model, 'TM_CODMON') ?>

    <?= $form->field($model, 'TM_CANTID') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

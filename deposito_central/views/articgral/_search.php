<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\ArticGralSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="artic-gral-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'AG_CODIGO') ?>

    <?= $form->field($model, 'AG_NOMBRE') ?>

    <?= $form->field($model, 'AG_CODMED') ?>

    <?= $form->field($model, 'AG_PRES') ?>

    <?= $form->field($model, 'AG_STACT') ?>

    <?php // echo $form->field($model, 'AG_STACDEP') ?>

    <?php // echo $form->field($model, 'AG_CODCLA') ?>

    <?php // echo $form->field($model, 'AG_FRACCQ') ?>

    <?php // echo $form->field($model, 'AG_PSICOF') ?>

    <?php // echo $form->field($model, 'AG_PTOMIN') ?>

    <?php // echo $form->field($model, 'AG_FPTOMIN') ?>

    <?php // echo $form->field($model, 'AG_PTOPED') ?>

    <?php // echo $form->field($model, 'AG_FPTOPED') ?>

    <?php // echo $form->field($model, 'AG_PTOMAX') ?>

    <?php // echo $form->field($model, 'AG_FPTOMAX') ?>

    <?php // echo $form->field($model, 'AG_CONSDIA') ?>

    <?php // echo $form->field($model, 'AG_FCONSDI') ?>

    <?php // echo $form->field($model, 'AG_RENGLON') ?>

    <?php // echo $form->field($model, 'AG_PRECIO') ?>

    <?php // echo $form->field($model, 'AG_REDOND') ?>

    <?php // echo $form->field($model, 'AG_PUNTUAL') ?>

    <?php // echo $form->field($model, 'AG_FPUNTUAL') ?>

    <?php // echo $form->field($model, 'AG_REPAUT') ?>

    <?php // echo $form->field($model, 'AG_ULTENT') ?>

    <?php // echo $form->field($model, 'AG_ULTSAL') ?>

    <?php // echo $form->field($model, 'AG_UENTDEP') ?>

    <?php // echo $form->field($model, 'AG_USALDEP') ?>

    <?php // echo $form->field($model, 'AG_PROVINT') ?>

    <?php // echo $form->field($model, 'AG_ACTIVO') ?>

    <?php // echo $form->field($model, 'AG_VADEM') ?>

    <?php // echo $form->field($model, 'AG_ORIGUSUA') ?>

    <?php // echo $form->field($model, 'AG_FRACSAL') ?>

    <?php // echo $form->field($model, 'AG_DROGA') ?>

    <?php // echo $form->field($model, 'AG_VIA') ?>

    <?php // echo $form->field($model, 'AG_DOSIS') ?>

    <?php // echo $form->field($model, 'AG_ACCION') ?>

    <?php // echo $form->field($model, 'AG_VISIBLE') ?>

    <?php // echo $form->field($model, 'AG_DEPOSITO') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

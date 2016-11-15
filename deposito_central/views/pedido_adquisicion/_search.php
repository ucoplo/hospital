<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Pedido_adquisicionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pedido-adquisicion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'PE_NUM') ?>

    <?= $form->field($model, 'PE_FECHA') ?>

    <?= $form->field($model, 'PE_HORA') ?>

    <?= $form->field($model, 'PE_COSTO') ?>

    <?= $form->field($model, 'PE_REFERENCIA') ?>

    <?php // echo $form->field($model, 'PE_NROEXP') ?>

    <?php // echo $form->field($model, 'PE_FECADJ') ?>

    <?php // echo $form->field($model, 'PE_DEPOSITO') ?>

    <?php // echo $form->field($model, 'PE_ARTDES') ?>

    <?php // echo $form->field($model, 'PE_ARTHAS') ?>

    <?php // echo $form->field($model, 'PE_CLASES') ?>

    <?php // echo $form->field($model, 'PE_TIPO') ?>

    <?php // echo $form->field($model, 'PE_EXISACT') ?>

    <?php // echo $form->field($model, 'PE_PEDPEND') ?>

    <?php // echo $form->field($model, 'PE_PONDHIS') ?>

    <?php // echo $form->field($model, 'PE_PONDPUN') ?>

    <?php // echo $form->field($model, 'PE_CLASABC') ?>

    <?php // echo $form->field($model, 'PE_DIASABC') ?>

    <?php // echo $form->field($model, 'PE_DIASPREVIS') ?>

    <?php // echo $form->field($model, 'PE_DIASDEMORA') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

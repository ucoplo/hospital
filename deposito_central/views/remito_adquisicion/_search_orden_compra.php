<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model quirofano\models\FojaSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="orden-compra-search">

    <?php $form = ActiveForm::begin([
        'action' => ['seleccion_orden_compra'],
        'method' => 'get',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-5',
                'wrapper' => 'col-sm-offset-5',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]);
   ?>

<div class="row">
    <div class="col-md-2"><?= $form->field($model, 'numero_oc')->label('Número')->widget(\yii\widgets\MaskedInput::className(), [
    'mask' => "9{0,6}"]) ?></div>
    <div class="col-md-2"><?= $form->field($model, 'ejercicio_oc')->label('Ejercicio')->widget(\yii\widgets\MaskedInput::className(), [
    'mask' => '9{0,4}',]) ?></div>

    <div class="col-md-6">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
    </div>

</div>
    <?php ActiveForm::end(); ?>

</div>
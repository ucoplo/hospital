<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\OrdenCompraSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="orden-compra-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
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
    ]); ?>

<div class="row">
    <div class="col-md-2"><?= $form->field($model, 'numero_oc')->label('Número') ?></div>
    <div class="col-md-2"><?= $form->field($model, 'ejercicio_oc')->label('Ejercicio') ?></div>  

    <div class="col-md-6">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        <?= Html::button('Limpiar', ['class' => 'btn btn-default','onclick'=>'js:$(".orden-compra-search input").val("");']) ?>
    </div>

</div>
    <?php ActiveForm::end(); ?>

</div>

<!-- <div class="orden-compra-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'numero_oc') ?>
    <?= $form->field($model, 'ejercicio_oc') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div> -->

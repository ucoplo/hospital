<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model farmacia\models\PacienteBuscar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="paciente-search">

    <?php $form = ActiveForm::begin([
        'id' => 'formBuscarPaciente',
        'action' => Url::to(['/paciente/index']),
        'method' => 'post',
        'layout' => 'horizontal',
    ]); ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'PA_TIPDOC', 
                    ['horizontalCssClasses' => ['label' => 'col-md-8', 'wrapper' => 'col-md-4']]) ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($model, 'PA_NUMDOC', 
                    ['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-6']]) ?>
            </div>
        </div>

        <div class="row">
            <?= $form->field($model, 'PA_APENOM', 
                ['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-8']]) ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'PA_HISCLI', 
                ['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-8']]) ?>
        </div>

        <div class="row">
            <div class="col-md-offset-10">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
            <!--button type="button" class="showModalButton btn btn-success" id="btnBuscar" style="margin-right:10px">Modificar</button-->
        </div>

    <?php ActiveForm::end(); ?>

</div>

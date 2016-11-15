<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Deposito */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deposito-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'DE_CODIGO')->textInput(['maxlength' => true,'readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'DE_DESCR')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Volver',['index'],array('class'=>'btn btn-primary'));?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

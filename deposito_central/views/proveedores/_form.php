<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Proveedores */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="proveedores-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'PR_CODIGO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PR_RAZONSOC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PR_TITULAR')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PR_CUIT')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PR_CONTACTO')->textInput(['maxlength' => true]) ?>

     <?= $form->field($model, 'PR_DOMIC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PR_TELEF')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PR_EMAIL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PR_CODRAFAM')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PR_OBS')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Volver',['index'],array('class'=>'btn btn-primary'));?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

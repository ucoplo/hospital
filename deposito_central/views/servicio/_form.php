<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Servicio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servicio-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'SE_CODIGO')->textInput(['maxlength' => true,'readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'SE_DESCRI')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SE_TPOSER')->radioList($model->lista_tipos()); ?>

    <?= $form->field($model, 'SE_CCOSTO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SE_SALA')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SE_AREA')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SE_INFO')->radioList(array('T' => 'Si', 'F' => 'No')); ?>

    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Volver',['index'],array('class'=>'btn btn-primary'));?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

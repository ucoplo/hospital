<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Labo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="labo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'LA_CODIGO')->textInput(['maxlength' => true,'readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'LA_NOMBRE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'LA_TIPO')->radioList(array('i' => 'Interno', 'e' => 'Externo')); ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Volver',['index'],array('class'=>'btn btn-primary'));?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

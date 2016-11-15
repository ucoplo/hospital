<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Accionterapeutica */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accionterapeutica-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'AC_COD')->textInput(['maxlength' => true,'readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'AC_DESCRI')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        
        <?= Html::a('Volver',['index'],array('class'=>'btn btn-primary'));?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

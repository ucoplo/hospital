<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Droga */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="droga-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'DR_CODIGO')->textInput(['maxlength' => true,'readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'DR_DESCRI')->textarea(['rows' => 6]) ?>

    <!--<?= $form->field($model, 'DR_CLASE')->dropDownList($model->listaClases, ['prompt' => 'Seleccione Clase' ]);?>-->

    <?= $form->field($model, 'DR_CLASE')->widget(Select2::classname(), [
	    'data' => $model->listaClases,
	    'options' => ['placeholder' => 'Seleccione Clase ...'],
	    'pluginOptions' => [
	        'allowClear' => true
	    ],
	]);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Volver',['index'],array('class'=>'btn btn-primary'));?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

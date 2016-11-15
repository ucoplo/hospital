<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Medic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="medic-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ME_CODIGO')->textInput(['maxlength' => true,'readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'ME_NOMCOM')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ME_CODKAI')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ME_CODRAF')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ME_KAIBAR')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ME_KAITRO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ME_CODMON')->dropDownList($model->listaMonodrogas, ['prompt' => 'Seleccione Monodroga' ]);?>       

    <?= $form->field($model, 'ME_CODLAB')->dropDownList($model->listaLaboratorios, ['prompt' => 'Seleccione Laboratorio' ]);?>       

    <?= $form->field($model, 'ME_PRES')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ME_FRACCQ')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ME_VALVEN')->textInput() ?>

    <?= $form->field($model, 'ME_ULTCOM')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Seleccione una fecha ...'],
            'removeButton' => false,
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ]);
    ?>

    <?= $form->field($model, 'ME_VALCOM')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ME_ULTSAL')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Seleccione una fecha ...'],
            'removeButton' => false,
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ]);
    ?>

    <?= $form->field($model, 'ME_STMIN')->textInput() ?>

    <?= $form->field($model, 'ME_STMAX')->textInput() ?>

    <?= $form->field($model, 'ME_RUBRO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ME_UNIENV')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ME_DEPOSITO')->dropDownList($model->listaDepositos, ['prompt' => 'Seleccione Depósito' ]);?>   

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Volver',['index'],array('class'=>'btn btn-primary'));?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

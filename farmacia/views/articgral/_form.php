<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\jui\DatePicker;



use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model farmacia\models\ArticGral */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="artic-gral-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'AG_CODIGO')->textInput(['maxlength' => true,'readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'AG_NOMBRE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_CODMED')->dropDownList($model->listaMedicamentos, ['prompt' => 'Seleccione Medicamento' ]);?>

    <?= $form->field($model, 'AG_PRES')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'AG_STACT')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_STACDEP')->textInput(['maxlength' => true]) ?>
  
    <?= $form->field($model, 'AG_CODCLA')->dropDownList($model->listaClases, ['prompt' => 'Seleccione Clase' ]);?>

    <?= $form->field($model, 'AG_FRACCQ')->radioList(array('S' => 'Si', 'N' => 'No')); ?>

    <?= $form->field($model, 'AG_PSICOF')->radioList(array('S' => 'Si', 'N' => 'No')); ?>

    <?= $form->field($model, 'AG_PTOMIN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_FPTOMIN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_PTOPED')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_FPTOPED')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_PTOMAX')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_FPTOMAX')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_CONSDIA')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_FCONSDI')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_RENGLON')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'AG_PRECIO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_REDOND')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_PUNTUAL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_FPUNTUAL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_REPAUT')->radioList(array('T' => 'Si', 'F' => 'No')); ?>
    
    <?//Fechas?>

    <?= $form->field($model, 'AG_ULTENT')->widget(DateControl::classname(), [
            'type'=>DateControl::FORMAT_DATE,
            
            'ajaxConversion'=>false,
            'options' => [
                'removeButton' => false,
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]
        ]);
    ?>
     <?= $form->field($model, 'AG_ULTSAL')->widget(DateControl::classname(), [
            'type'=>DateControl::FORMAT_DATE,
            
            'ajaxConversion'=>false,
            'options' => [
                'removeButton' => false,
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]
        ]);
    ?>
     <?= $form->field($model, 'AG_UENTDEP')->widget(DateControl::classname(), [
            'type'=>DateControl::FORMAT_DATE,
            
            'ajaxConversion'=>false,
            'options' => [
                'removeButton' => false,
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]
        ]);
    ?>
    <?= $form->field($model, 'AG_USALDEP')->widget(DateControl::classname(), [
            'type'=>DateControl::FORMAT_DATE,
            
            'ajaxConversion'=>false,
            'options' => [
                'removeButton' => false,
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]
        ]);
    ?>

   

    
    <?= $form->field($model, 'AG_PROVINT')->dropDownList($model->listaServicios, ['prompt' => 'Seleccione Proveedor' ]);?>

    <?= $form->field($model, 'AG_ACTIVO')->radioList(array('T' => 'Si', 'F' => 'No')); ?>

    <?= $form->field($model, 'AG_VADEM')->radioList(array('S' => 'Si', 'N' => 'No')); ?>

    <?= $form->field($model, 'AG_ORIGUSUA')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AG_FRACSAL')->radioList(array('S' => 'Si', 'N' => 'No')); ?>

    <?= $form->field($model, 'AG_DROGA')->dropDownList($model->listaDrogas, ['prompt' => 'Seleccione Droga' ]);?>

    <?= $form->field($model, 'AG_VIA')->dropDownList($model->listaVias, ['prompt' => 'Seleccione Vía' ]);?>

    <?= $form->field($model, 'AG_DOSIS')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'AG_ACCION')->dropDownList($model->listaAccionTerapeutica, ['prompt' => 'Seleccione Acción Terapéutica' ]);?>    
    
    <?= $form->field($model, 'AG_VISIBLE')->radioList(array('T' => 'Si', 'F' => 'No')); ?>

    <?= $form->field($model, 'AG_DEPOSITO')->dropDownList($model->listaDepositos, ['prompt' => 'Seleccione Depósito' ]);?>    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Volver',['index'],array('class'=>'btn btn-primary'));?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

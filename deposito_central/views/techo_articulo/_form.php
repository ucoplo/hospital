<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\web\JsExpression;
use deposito_central\models\ArticGral;
/* @var $this yii\web\View */
/* @var $model deposito_central\models\Techo_articulo */
/* @var $form yii\widgets\ActiveForm */
?>

     

<div class="techo-articulo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'TA_CODSERV')->dropDownList($model->listaServicio, ['prompt' => 'Seleccione Servicio' ]);?>

    <?= $form->field($model, 'TA_DEPOSITO')->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito' ]);?>

    <?php  // Get the initial city description
    $url = \yii\helpers\Url::to(['query']);
    if (empty($model->TA_DEPOSITO) || empty($model->TA_CODART)){
    	$articuloDesc = '';
    }else{
    	$articulo = ArticGral::findOne($model->TA_CODART,$model->TA_DEPOSITO);
    	$articuloDesc = $articulo->AG_CODIGO.'-'.$articulo->AG_NOMBRE;
    }
    //$articuloDesc = (empty($model->TA_DEPOSITO) || empty($model->TA_CODART)) ? '' : ArticGral::findOne($model->TA_CODART,$model->TA_DEPOSITO)->AG_NOMBRE; 
    echo $form->field($model, 'TA_CODART')->widget(Select2::classname(), [
    	'initValueText' => $articuloDesc, // set the initial display text
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Esperando resultados...'; }"),
            ],
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
             'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
             'templateResult' => new JsExpression('function(paciente) { return paciente.text; }'),
             'templateSelection' => new JsExpression('function (paciente) { return paciente.text; }'),
        ],
    ]);
    ?>


    
    <?= $form->field($model, 'TA_CANTID')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

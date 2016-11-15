<?php

use yii\helpers\Html;

use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

use yii\widgets\Pjax;
use kartik\checkbox\CheckboxX;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use deposito_central\assets\Pedido_adquisicionAsset;

Pedido_adquisicionAsset::register($this);

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Pedido_adquisicion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pedido-adquisicion-form">

    <?php 
    $form = ActiveForm::begin([
            'action' => ['create'],
            'method' => 'post',
            'layout' => 'horizontal'
        ]); ?>


  <?= $form->errorSummary($model); ?>

            <?= $form->field($model, 'PE_DEPOSITO')->dropDownList($model->listaDeposito);?>
   
    

   <?= $form->field($model, 'PE_CLASES')->widget(Select2::classname(), [
        'data' => $model->listaClases,
        'options' => ['placeholder' => 'Seleccione Clase ...','multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

  
    <?php
    $artDesc = empty($model->PE_ARTDES) ? '' : ArticGral::findOne($model->PE_ARTDES,$model->PE_DEPOSITO)->AG_NOMBRE;
    $url_busqueda_articulos = \yii\helpers\Url::to(['pedido_adquisicion/buscar-articulos']);
  
    echo $form->field($model, 'PE_ARTDES')->widget(Select2::classname(), [
        'initValueText' => $artDesc, // set the initial display text
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'ajax' => [
                'url' => $url_busqueda_articulos,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) {
                  return {q:params.term};
                }')
            ],
            
            'enableEmpty' => true,
            'minimumInputLength' => 1,
            'language' => 'es',
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(articulo) { return articulo.text; }'),
            'templateSelection' => new JsExpression('function (articulo) { return articulo.text; }'),
        ],
    ]);?>
    <?php
    $artDesc = empty($model->PE_ARTHAS) ? '' : ArticGral::findOne($model->PE_ARTHAS,$model->PE_DEPOSITO)->AG_NOMBRE;
    $url_busqueda_articulos = \yii\helpers\Url::to(['pedido_adquisicion/buscar-articulos']);
  
    echo $form->field($model, 'PE_ARTHAS')->widget(Select2::classname(), [
        'initValueText' => $artDesc, // set the initial display text
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'ajax' => [
                'url' => $url_busqueda_articulos,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) {
                  return {q:params.term};
                }')
            ],
            
            'enableEmpty' => true,
            'minimumInputLength' => 1,
            'language' => 'es',
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(articulo) { return articulo.text; }'),
            'templateSelection' => new JsExpression('function (articulo) { return articulo.text; }'),
        ],
    ]);?>
   
    <?= $form->field($model, 'PE_TIPO')->widget(CheckboxX::classname(), [
                                 'autoLabel' => false, 'pluginOptions'=>['threeState'=>false]]); ?>

    <?= $form->field($model, 'PE_CLASABC')->widget(CheckboxX::classname(), [
                                 'autoLabel' => false, 'pluginOptions'=>['threeState'=>false]]); ?>
    
    <?= $form->field($model, 'PE_DIASABC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PE_EXISACT')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]); ?>

    <?= $form->field($model, 'PE_PEDPEND')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]); ?>

    <?= $form->field($model, 'PE_PONDHIS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PE_PONDPUN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PE_DIASPREVIS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PE_DIASDEMORA')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PE_REFERENCIA')->textarea(['rows' => 3]) ?>
    


   
    <div class="form-group">
        <?= Html::submitButton('Calcular renglones', ['class'=>'btn btn-success']) ?>
        
    </div>

    <?php ActiveForm::end(); ?>

</div>

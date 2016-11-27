<?php

use yii\helpers\Html;

use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

use yii\widgets\Pjax;
use kartik\checkbox\CheckboxX;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;

use deposito_central\models\ArticGral;
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


    <div class="row">
        <div class="col-md-5"> 
            <?= $form->field($model, 'PE_DEPOSITO',['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-9']])->dropDownList($model->listaDeposito);?>
        </div>  
        <div class="col-md-7">
            <?= $form->field($model, 'PE_CLASES',['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-9']])->widget(Select2::classname(), [
                'data' => $model->listaClases,
                'options' => ['placeholder' => 'Seleccione Clase ...','multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
        </div> 
    </div> 
    <div class="row">
        <div class="col-md-6"> 
            <?php
            //$artDesc = empty($model->PE_ARTDES) ? '' : ArticGral::findOne($model->PE_ARTDES,$model->PE_DEPOSITO)->AG_NOMBRE;
            $url_busqueda_articulos = \yii\helpers\Url::to(['pedido_adquisicion/buscar-articulos']);
          
            echo $form->field($model, 'PE_ARTDES')->widget(Select2::classname(), [
                //'initValueText' => $artDesc, // set the initial display text
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
        </div>  
        <div class="col-md-6">
            <?php
            $artDesc = empty($model->PE_ARTHAS) ? '' : ArticGral::findOne($model->PE_ARTHAS,$model->PE_DEPOSITO)->AG_NOMBRE;
            $url_busqueda_articulos = \yii\helpers\Url::to(['pedido_adquisicion/buscar-articulos']);
          
            echo $form->field($model, 'PE_ARTHAS')->widget(Select2::classname(), [
                //'initValueText' => $artDesc, // set the initial display text
                'options' => ['placeholder' => ''],
                'data' => (!empty($data['PE_ARTHAS']))?[ "{$data['PE_ARTHAS']}" => "[{$data['PE_ARTHAS']}] ".$artDesc]:[],
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
        </div>  
    </div>          

     <div class="row">
  
        <div class="col-md-4">
         <?= $form->field($model, 'PE_ACTIVOS',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-4']])->widget(CheckboxX::classname(), [
                             'autoLabel' => false, 'pluginOptions'=>['threeState'=>false]]); ?>
        </div>  
        <div class="col-md-4">
         <?= $form->field($model, 'PE_INACTIVOS',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-4']])->widget(CheckboxX::classname(), [
                             'autoLabel' => false, 'pluginOptions'=>['threeState'=>false]]); ?>
        </div>  
    </div>                   

    <div class="row">
        <div class="col-md-2"> 
             <?= $form->field($model, 'CLASE_A',['horizontalCssClasses' => ['label' => 'col-md-8', 'wrapper' => 'col-md-4']])->widget(CheckboxX::classname(), [
                             'autoLabel' => false, 'pluginOptions'=>['threeState'=>false]]); ?>
        </div>
        <div class="col-md-2"> 
             <?= $form->field($model, 'CLASE_B',['horizontalCssClasses' => ['label' => 'col-md-8', 'wrapper' => 'col-md-4']])->widget(CheckboxX::classname(), [
                             'autoLabel' => false, 'pluginOptions'=>['threeState'=>false]]); ?>
        </div>
        <div class="col-md-2"> 
             <?= $form->field($model, 'CLASE_C',['horizontalCssClasses' => ['label' => 'col-md-8', 'wrapper' => 'col-md-4']])->widget(CheckboxX::classname(), [
                             'autoLabel' => false, 'pluginOptions'=>['threeState'=>false]]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'PE_DIASABC',['horizontalCssClasses' => ['label' => 'col-md-9', 'wrapper' => 'col-md-3']])
                            ->widget(\yii\widgets\MaskedInput::className(), ['mask' => "9{0,3}"]) ?> 
        </div>
    </div>        

    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'PE_EXISACT',['horizontalCssClasses' => ['label' => 'col-md-8', 'wrapper' => 'col-md-4']])->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]); ?>
        </div>  
        <div class="col-md-6">
            <?= $form->field($model, 'PE_PEDPEND',['horizontalCssClasses' => ['label' => 'col-md-8', 'wrapper' => 'col-md-4']])->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]); ?>
        </div>  
    </div> 
    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'PE_PONDHIS',['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-4']])
                            ->widget(\yii\widgets\MaskedInput::className(), ['mask' => "9{0,3}"]) ?> 
        </div>  
        <div class="col-md-6">
            <?= $form->field($model, 'PE_PONDPUN',['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-4']])
                            ->widget(\yii\widgets\MaskedInput::className(), ['mask' => "9{0,3}"]) ?> 
        </div>  
    </div> 

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'PE_DIASPREVIS',['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-4']])
                            ->widget(\yii\widgets\MaskedInput::className(), ['mask' => "9{0,3}"]) ?> 
       </div>  
        <div class="col-md-6">
            <?= $form->field($model, 'PE_DIASDEMORA',['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-4']])
                            ->widget(\yii\widgets\MaskedInput::className(), ['mask' => "9{0,3}"]) ?> 
        </div>  
    </div> 
  

   

    <?= $form->field($model, 'PE_REFERENCIA')->textarea(['rows' => 3]) ?>
    

    <div class="row">
        <div class="col-md-6 text-left">
            <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-danger']) ?>
        </div>
        <div class="col-md-6 text-right">
            <?= Html::submitButton('Calcular renglones', ['class'=>'btn btn-success']) ?>        
        </div>
    </div>
   
    

    <?php ActiveForm::end(); ?>

</div>

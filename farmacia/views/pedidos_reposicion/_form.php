<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

use yii\widgets\Pjax;
use yii\grid\GridView;



/* @var $this yii\web\View */
/* @var $model farmacia\models\Pedentre */
/* @var $form yii\widgets\ActiveForm */
/* @var $renglones yii\data\ActiveDataProvider */
?>


<div class="pedentre-form">

    <?php yii\widgets\Pjax::begin(['id' => 'nuevo_pedido']) ?>
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ]]); ?>
   
    
 


    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'PE_NROPED', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->textInput(['readonly' => 'true','maxlength' => true,'value' => str_pad($model->PE_NROPED, 6, '0', STR_PAD_LEFT)]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'PE_FECHA')->widget(DateControl::classname(), [
                'type'=>DateControl::FORMAT_DATE,
               'disabled'=>true,
                'ajaxConversion'=>false,
                'options' => [
                    'removeButton' => false,
                    'options' => ['placeholder' => 'Seleccione una fecha ...'],
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ]);?>
        </div>

       
    </div>
    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'dias_reponer', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'PE_DEPOSITO')->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito' ]);?>
            
        </div>

       
    </div>
   <?= $form->field($model, 'incluye_demanda_insatisfecha')->inline()->radioList(array('S' => 'Si', 'N' => 'No')

   ); ?>

  

   <?= $form->field($model, 'PE_CLASE')->widget(Select2::classname(), [
        'data' => $model->listaClases,
        'options' => ['placeholder' => 'Seleccione Clase ...','multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>
   

    <?= $form->field($model, 'PE_REFERENCIA')->textarea(['rows' => 3]) ?>
    
    
    <div class="form-group">
        <?= Html::submitButton('Generar Pedido', ['name' => 'btngenerar','class' => 'btn btn-success']) ?>
    </div>

    
    
   <div id="grid_pedidos_renglones">
    <?= GridView::widget([
        'dataProvider' => $renglones,
        'emptyText' => '',
        'columns' => [
            

            [
                'attribute' => 'PE_NRORENG',
                'value' => function($model){
                    return Html::textInput("renglones[$model->PE_NRORENG][PE_NRORENG]",$model->PE_NRORENG,['readonly' => 'true']);
                },
                'format' => 'raw',
                'label' => 'Renglón',
            ],      
            [
                'attribute' => 'PE_CODMON',
                'value' => function($model){
                    return Html::textInput("renglones[$model->PE_NRORENG][PE_CODMON]",$model->PE_CODMON,['readonly' => 'true']);
                },
                'format' => 'raw',
                'label' => 'Código',
            ],     
              [
                'attribute' => 'descripcion',
                            
                'label' => 'Descripción',
            ],    
              [
                'attribute' => 'PE_CANTPED',
                'value' => function($model){
                    return Html::textInput("renglones[$model->PE_NRORENG][PE_CANTPED]",$model->PE_CANTPED,['readonly' => 'true']);
                },
                'format' => 'raw',
                'label' => 'Cantidad pedida',
            ],    
            
            
         
          
        ],
    ]); ?>
    </div>
   
    <?=  $form->field($model, 'pedido_renglones')->hiddenInput(['value'=> 'renglones'])->label(false);?>
    <div class="form-group text-right">
        <?= Html::submitButton('Guardar', ['name' => 'btnguardar',
                                            'id' => 'btnguardar',
                                            'class' => 'btn btn-success',
                                            'data' => [
                'confirm' => '¿Está seguro de realizar el Pedido?',
                'method' => 'post',
            ],]) ?>
    </div>
    <?php ActiveForm::end(); ?>
     <?php Pjax::end(); ?>
</div>

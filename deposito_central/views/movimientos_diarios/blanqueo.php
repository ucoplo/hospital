<?php

use yii\helpers\Html;

use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use unclead\widgets\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model deposito_central\models\Movimientos_diarios */

$this->title = 'Blanqueo Stock Lotes - Artículos';
$this->params['breadcrumbs'][] = ['label' => 'Blanqueo Stock Lotes', 'url' => ['blanquear_stock']];

?>
<div class="movimientos-diarios-update">

    <h1><?= Html::encode($this->title) ?></h1>

   <div class="movimientos-diarios-form">

	       <?php 
		    $form = ActiveForm::begin([
		           
		            'fieldConfig' => [
				        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
				        'horizontalCssClasses' => [
				            'label' => 'col-sm-4',
				            'offset' => 'col-sm-offset-4',
				            'wrapper' => 'col-sm-8',
				            'error' => '',
				            'hint' => '',
				        ],
				    ],
		            'layout' => 'horizontal'
		        ]); ?>
        
	    <div class="row">
	            <div class="col-md-6">
	                <?= $form->field($model, 'DM_FECHA')->widget(DateControl::classname(), [
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
	        	 <?php $model->deposito_descripcion = ((isset($model->DM_DEPOSITO))?$model->deposito->DE_DESCR:'');?>
	            <?= $form->field($model, 'deposito_descripcion')->textInput(['disabled'=>true,]) ?>

	             <?= $form->field($model, 'DM_DEPOSITO')->hiddenInput()->label(false);?>
	           
	        </div>
	    </div>

	     <?= $form->field($model, 'renglones', ['horizontalCssClasses' => ['label' => 'col-md-0', 'wrapper' => 'col-md-12 col-sm-offset-0']]	)->widget(MultipleInput::className(), [
	                //'limit' => 4,
	                'addButtonPosition' => MultipleInput::POS_HEADER,
	                'enableGuessTitle'  => true,
	                'allowEmptyList' => true,
	                'columns' => [
	                     [
	                        'name'  => 'DM_CODART',
	                        
	                        'title' => 'Cod. Medicamento',
	                        'type' => kartik\select2\Select2::classname(),
	                        'options' => [
	                        'data' => ArrayHelper::map($model->listaMedicamentos, 'AG_CODIGO', 
	                                            function($model, $defaultValue) {
	                                                return $model['AG_CODIGO'].'-'.$model['AG_NOMBRE'];
	                                            }),
	                            'options' => ['placeholder' => ''],
	                            'pluginOptions' => [
	                                'allowClear' => false,
	                                'templateSelection' => new JsExpression('function(monodroga) { return monodroga.text.substring(0,4); }'),
	                            ],
	                            'pluginEvents' => [
	                                "select2:select" => "function(name) {
	                                                        descripcion = name.params.data.text.substring(5); 
	                                                        $(this).closest('td').next().find('input').val(descripcion);
	                                                    }",
	                            ],
	                        ]
	                    ],
	                    [
	                        'name' => 'descripcion',
	                        'enableError' => true,
	                        'value' => function($data) {
	                                return $data['descripcion'];
	                            },
	                        'options' => [
	                            'readonly' => true,
	                        ],
	                        'title' => 'Descripción del medicamento',
	                    ],
	                   
	                ]
	             ])->label(false);
	            ?>


	   <div class="form-group text-right" id="wrp_guardar">
	                <?= Html::submitButton('Blanquear', ['name' => 'btnguardar',
	                                                    'id' => 'btnguardar',
	                                                    'class' => 'btn btn-success',
	                                                    'data' => [
	                        'confirm' => '¿Confirma el Blanqueo de Stock?',
	                        'method' => 'post',
	                    ],]) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>


</div>

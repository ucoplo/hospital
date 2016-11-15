<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use unclead\widgets\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model farmacia\models\Movimientos_diarios */

$this->title = 'Blanqueo Stock Lotes - Medicamentos';
$this->params['breadcrumbs'][] = ['label' => 'Blanqueo Stock Lotes', 'url' => ['blanquear_stock']];

?>
<div class="movimientos-diarios-update">

    <h1><?= Html::encode($this->title) ?></h1>

   <div class="movimientos-diarios-form">

	    <?php $form = ActiveForm::begin(); ?>
	    <div class="row">
	            <div class="col-md-3">
	                <?= $form->field($model, 'MD_FECHA')->widget(DateControl::classname(), [
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
	        <div class="col-md-3">
	            
	             <?= $form->field($model, 'MD_DEPOSITO')->hiddenInput()->label(false);?>
	            <?= Html::label('Depósito', 'deposito') ?>
	            <?php $deposito = ((isset($model->MD_DEPOSITO))?$model->deposito->DE_DESCR:'');?>
	            <?= Html::input('text', 'deposito',$deposito, ['class'=>'form-control','readonly'=>true]) ?> 
	            <div class="help-block"></div>
	        </div>
	    </div>

	     <?= $form->field($model, 'renglones')->widget(MultipleInput::className(), [
	                //'limit' => 4,
	                'addButtonPosition' => MultipleInput::POS_HEADER,
	                'enableGuessTitle'  => true,
	                'allowEmptyList' => true,
	                'columns' => [
	                     [
	                        'name'  => 'MD_CODMON',
	                        
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
	             ]);
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

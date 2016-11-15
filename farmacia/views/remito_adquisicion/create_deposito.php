<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use farmacia\models\ArticGral;
use yii\widgets\MaskedInput;
use unclead\widgets\MultipleInput;
/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_Adquisicion */

$this->title = 'Adquisición Recibida desde Depósito Central';
$this->params['breadcrumbs'][] = ['label' => 'Remitos de Adquisición', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remito--adquisicion-create">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>

   <div class="remito--adquisicion-form">

	    <?php $form = ActiveForm::begin([
		    'options' => [
		        'id' => 'form-create-deposito',
		        'data-pjax' => true,
		    ],
		    'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-md-3',
                    'wrapper' => 'col-md-4'
                ]
            ],
		    'layout' => 'horizontal',
		]); ?>

	    <div class="row">
        	<div class="col-md-6">
        		<?= $form->field($model, 'RE_FECHA')->widget(DateControl::classname(), [
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
        		<?= $form->field($model, 'RE_HORA')->textInput([ 'readonly' => 'true']) ?>
	 		</div>
        </div>

        <div class="row">
        	<div class="col-md-6">
				<?= $form->field($model, 'RE_CONCEP',['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-7']])->textarea(['rows' => 3,'readonly' => 'true']) ?>
			</div>
        </div>		
		<div class="row">
        	<div class="col-md-6">
				<?= $form->field($model, 'RE_DEPOSITO',['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-7']])->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito']);?>
			</div>
        </div>

	     <div id="grid_adquisicion_renglones">
	
	            
	         
	          
	    </div>
		<?= $form->field($model, 'renglones', ['horizontalCssClasses' => ['label' => 'col-md-0', 'wrapper' => 'col-md-12 col-sm-offset-0']])->widget(MultipleInput::className(), [
		    //'limit' => 4,
		    'addButtonPosition' => MultipleInput::POS_HEADER,
		    'enableGuessTitle'  => true,
		    'addButtonOptions' => [
            	'class' => 'hide hidden ',
	            'label' => '' 
	        ],
	        'removeButtonOptions' => [
	            'label' => '',
	            'class' => 'hide hidden',
	        ],
		    'columns' => [
		
		        [
	                'name' => 'RM_CODMON',
	                
	                'options' => [
	                	'readonly' => true,
	                ],
	                'value' => function($data) {
		                return $data['RM_CODMON'];
		            },	     	
	                'title' => 'Cod. Medicamento',
	                 'headerOptions' => [
		                'style' => 'width: 150px;',
		                'class' => 'day-css-class'
		            ]
	            ],    
		        [
	                'name' => 'descripcion',
	                
	                'options' => [
	                	'readonly' => true,
	                ],
	                'value' => function($data) {
		                return $data['descripcion'];
		            },	     	
	                'title' => 'Descripción del medicamento',
	            ],    
		        [
		            'name'  => 'RM_FECVTO',
		            'title' => 'Fecha Vto',
		            'value' => function($data) {
		                return $data['RM_FECVTO'];
		            },
		            
		           'options' => [
	                	'readonly' => true,
	                ],
		           
		         'options' => [
	                	'readonly' => true,
	                ],   'headerOptions' => [
		                'style' => 'width: 200px;',
		                'class' => 'day-css-class'
		            ]
		        ],
		       	[
		            'name'  => 'precio_compra',
		            'options' => [
	                	'readonly' => true,
	                ],
	                 'value' => function($data) {
		                return $data['precio_compra'];
		            },
		            'title' => 'Precio',
		            'headerOptions' => [
		                'style' => 'width: 100px;',
		            ]
		        ],
		         [
		            'name'  => 'RM_CANTID',
		         	  'enableError' => true,
		           
		            'title' => 'Cantidad',
		          
		            'headerOptions' => [
		                'style' => 'width: 200px;',
		                
		            ]
		            
		            
		        ],
		        
		    ]
		 ])->label(false);
		?>

	    <?=  $form->field($model, 'RE_REMDEP')->hiddenInput()->label(false);?>

	    <div class="form-group text-right">
	        <?= Html::submitButton('Guardar', ['name' => 'btnguardar',
	                                            'id' => 'btnguardar',
	                                            'class' => 'btn btn-success',
	                                            'data' => [
	                'confirm' => '¿Confirma la Adquisición?',
	                'method' => 'post',
	            ],]) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>


</div>

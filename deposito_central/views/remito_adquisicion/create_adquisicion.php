<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use deposito_central\models\ArticGral;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use kartik\select2\Select2;
use unclead\widgets\MultipleInput;
use deposito_central\assets\Remito_adquisicionAsset;

Remito_adquisicionAsset::register($this);

/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_Adquisicion */

$this->title = 'Adquisición de Origen Externo';
$this->params['breadcrumbs'][] = ['label' => 'Remitos de Adquisición', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remito--adquisicion-create">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>

   <div class="remito--adquisicion-form">

	    
	    <?php $form = ActiveForm::begin([
		    'options' => [
		        'id' => 'form-create-externo',
		        'data-pjax' => true,
		    ],
		    'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-md-3',
                    'wrapper' => 'col-md-9'
                ]
            ],
		    'layout' => 'horizontal',
		]); ?>
		
        
        
	    <div class="row">
        	<div class="col-md-6">
        		<?= $form->field($model, 'RA_FECHA')->widget(DateControl::classname(), [
	                'type'=>DateControl::FORMAT_DATE,
	               'disabled'=>true,
	                'ajaxConversion'=>false,
	                'options' => [
	                    'removeButton' => false,
	                    'options' => ['placeholder' => 'Seleccione una fecha ...'],
	                    'pluginOptions' => [
	                        'autoclose' => true,

	                    ]
	                ]
	            ]);?>
        		
        	</div>
        </div>
        <div class="row">
        	<div class="col-md-6">
        		<?= $form->field($model, 'RA_HORA')->textInput( ['readonly' => 'true']) ?>
	 		</div>
        </div>

        <div class="row">
        	<div class="col-md-6">
				<?= $form->field($model, 'RA_CONCEP')->textarea(['rows' => 3]) ?>
			</div>
			<div class="col-md-6">

				<?= $form->field($model, 'RA_TIPMOV',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])->radioList(array('C' => 'Compra', 'D' => 'Donación')

				); ?>
			</div>
        </div>		
		<div class="row">
        	<div class="col-md-6">
				<?= $form->field($model, 'RA_DEPOSITO')->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito' ]);?>
			</div>
        </div>

	  	   
	    <?= $form->field($model, 'renglones', ['horizontalCssClasses' => ['label' => 'col-md-0', 'wrapper' => 'col-md-12 col-sm-offset-0']])->widget(MultipleInput::className(), [
		    //'limit' => 4,
		    'addButtonPosition' => MultipleInput::POS_HEADER,
		    'enableGuessTitle'  => true,
		    'columns' => [
		    	 [
		            'name'  => 'AR_CODART',
		            'enableError' => true,
		            'title' => 'Cod. Artículo',
		            'type' => kartik\select2\Select2::classname(),
		    
		             'options' => function($data) use ($model){
                        $deposito = $model->RA_DEPOSITO;
                        $url_busqueda_articulos = \yii\helpers\Url::to(['remito_adquisicion/buscar-articulos']);
                        return 
                        [
                            //'data' => (!empty($data['AR_CODART']))?[ "{$data['AR_CODART']}" => "[{$data['AR_CODART']}] ".$data['descripcion']]:[],
                            'pluginOptions' => [
						        'allowClear' => false,
						        'templateSelection' => new JsExpression('function(monodroga) { return monodroga.text.substring(0,4); }'),
                                'ajax' => [
                                    'url' => $url_busqueda_articulos,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) {
                                            deposito_id = 0;
                                            deposito_id = $("#remito_adquisicion-ra_deposito").val();
                                            if (deposito_id==0) {
                                                krajeeDialog.alert("Debe seleccionar primero el depósito");
                                                return false;
                                            }else{
                                                return {q:params.term,deposito:deposito_id};
                                            }
                                    }')
                                ],
                                
                                'enableEmpty' => true,
                                'minimumInputLength' => 1,
                                'language' => 'es',
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(articulo) { return articulo.text; }'),
                                'templateSelection' => new JsExpression('function (articulo) { return articulo.id; }'),
                            ],
                            'pluginEvents' => [

                                "select2:select" => "function(result) {
                                    
                                    if (!codigo_unico($(this)))
                                    {
                                        $(this).val('').trigger('change');
                                        krajeeDialog.alert('No puede repetirse el articulo');
                                    }
                                    else{
                                    	descripcion = result.params.data.text.substring(6); 
    		 							$(this).closest('td').next().find('input').val(descripcion);
                                    }
                                }",
                            ]
                        ];
                    },
		        ],
		        [
	                'name' => 'descripcion',
	                'enableError' => true,
	                'options' => [
	                	'readonly' => true,
	                ],
	                          	
	                'title' => 'Descripción del artículo',
	            ],    
		        [
		            'name'  => 'AR_FECVTO',
		            'type'  => \kartik\date\DatePicker::className(),
		            'title' => 'Fecha Vto',
		            'value' => function($data) {
		                return $data['AR_FECVTO'];
		            },
		            
		            'options' => [
		            	'removeButton' => false,
		                'pluginOptions' => [
		                    'format' => 'dd-mm-yyyy',
		                    'todayHighlight' => true,

		                ]
		            ],
		            'headerOptions' => [
		                'style' => 'width: 200px;',
		                'class' => 'day-css-class'
		            ]
		        ],
		       	[
		            'name'  => 'precio_compra',
		            
	                 'value' => function($data) {
		                return $data['precio_compra'];
		            },
		            'title' => 'Precio',
		            'headerOptions' => [
		                'style' => 'width: 100px;',
		            ]
		        ],
		         [
		            'name'  => 'AR_CANTID',
		            'enableError' => true,
		           
		            'title' => 'Cantidad',
		            'headerOptions' => [
		                'style' => 'width: 200px;',
		                
		            ]
		            
		            
		        ],
		        
		    ]
		 ])->label(false);
		?>
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

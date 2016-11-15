<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use farmacia\models\ArticGral;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use kartik\select2\Select2;
use unclead\widgets\MultipleInput;
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
		<?= $form->errorSummary($model); ?>
        
        
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
	                        'autoclose' => true,

	                    ]
	                ]
	            ]);?>
        		
        	</div>
        </div>
        <div class="row">
        	<div class="col-md-6">
        		<?= $form->field($model, 'RE_HORA')->textInput( ['readonly' => 'true']) ?>
	 		</div>
        </div>

        <div class="row">
        	<div class="col-md-6">
				<?= $form->field($model, 'RE_CONCEP')->textarea(['rows' => 3]) ?>
			</div>
			<div class="col-md-6">

				<?= $form->field($model, 'RE_TIPMOV',['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])->radioList(array('C' => 'Compra', 'D' => 'Donación')

				); ?>
			</div>
        </div>		
		<div class="row">
        	<div class="col-md-6">
				<?= $form->field($model, 'RE_DEPOSITO')->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito' ]);?>
			</div>
        </div>

	     <!--<div id="grid_adquisicion_renglones">
	     	 <div class="form-group ">
	    			<?= Html::a('Agregar Renglón','#',array('class'=>'btn btn-primary','id' => 'btn_add_renglon'));?>
	    	</div>

	    <?/*= GridView::widget([
	        'dataProvider' => $model->renglones,
	        'emptyText' => '',
	        'summary'=>"",
	        'columns' => [

	            
	            [
	                'attribute' => 'RM_CODMON',
	                'value' => function($model){
	                	return  Select2::widget([
								    'name' => "Remito_Adquisicion[renglones][$model->RM_NUMRENG][RM_CODMON]",
								    'id' => "renglon$model->RM_NUMRENG",
								    'attribute' => 'AG_CODIGO',

								    'data' => ArrayHelper::map(ArticGral::find()->all(), 'AG_CODIGO', 
								    	function($model, $defaultValue) {
									        return $model['AG_CODIGO'].'-'.$model['AG_NOMBRE'];
									    }),
								    'options' => ['placeholder' => 'Seleccione...'],
								    'pluginOptions' => [
								        'allowClear' => false
								    ],
								    
								]);
	                	//return Html::dropDownList("renglones[$model->RM_NUMRENG][RM_CODMON]",null,ArrayHelper::map(ArticGral::find()->all(), 'AG_CODIGO', 'AG_NOMBRE'));
	                    //return Html::textInput("renglones[$model->RM_NUMRENG][RM_CODMON]",$model->RM_CODMON,['readonly' => 'true']);
	                },
	                'format' => 'raw',
	                'label' => 'Cod. Medicamento',
	            ],     
	              [
	                'attribute' => 'descripcion',
	                'value' => function($model){
	                    return Html::textInput("Remito_Adquisicion[renglones][$model->RM_NUMRENG][descripcion]",$model->descripcion,['readonly' => 'true','class'=> 'solo_lectura']);
	                },
	                'format' => 'raw',
	                          	
	                'label' => 'Descripción del medicamento',
	            ],    
	             [
	                'attribute' => 'RM_FECVTO',
	                'value' => function($model){
	                	return MaskedInput::widget(['name' => "Remito_Adquisicion[renglones][$model->RM_NUMRENG][RM_FECVTO]", 'clientOptions' => ['alias' =>  'dd/mm/yyyy']]);
	                    //return Html::textInput("renglones[$model->RM_NUMRENG][RM_FECVTO]",$model->RM_FECVTO,['readonly' => 'true']);
	                },
	                'format' => 'raw',
	                'label' => 'Fecha Vto',
	            ],    
	              [
	                'attribute' => 'RM_CANTID',
	                'value' => function($model){
	                	return MaskedInput::widget(['name' => "Remito_Adquisicion[renglones][$model->RM_NUMRENG][RM_CANTID]", 'clientOptions' => ['alias' =>  'decimal','groupSeparator' => ',', 'autoGroup' => true]]);
	                	
	                    //return Html::textInput("renglones[$model->RM_NUMRENG][RM_CANTID]",$model->RM_CANTID);
	                },
	                'format' => 'raw',
	                'label' => 'Cantidad',
	            ],    
	             [
	             	'value' => function($model){
	                	return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#',['class'=>'btn_delete_renglon']);
	                	
	                 
	                },
	                'format' => 'raw',
	                'label' => '',
	            ],    
	            
	         
	          
	        ],
	    ]); */?>
	    </div>-->
	   
	    <?= $form->field($model, 'renglones', ['horizontalCssClasses' => ['label' => 'col-md-0', 'wrapper' => 'col-md-12 col-sm-offset-0']])->widget(MultipleInput::className(), [
		    //'limit' => 4,
		    'addButtonPosition' => MultipleInput::POS_HEADER,
		    'enableGuessTitle'  => true,
		    'columns' => [
		    	 [
		            'name'  => 'RM_CODMON',
		            'enableError' => true,
		            'title' => 'Cod. Medicamento',
		            'type' => kartik\select2\Select2::classname(),
		            'options' => [
		                'data' => ArrayHelper::map(ArticGral::find()->all(), 'AG_CODIGO', 
								    	function($model, $defaultValue) {
									        return $model['AG_CODIGO'].'-'.$model['AG_NOMBRE'];
									    }),
					    'options' => ['placeholder' => ''],
					    'pluginOptions' => [
					        'allowClear' => false,
					        'templateSelection' => new JsExpression('function(monodroga) { return monodroga.text.substring(0,4); }'),
					    ],
					    'pluginEvents' => [
    						"select2:select" => "function(name) { descripcion = name.params.data.text.substring(5); 
    															  $(this).closest('td').next().find('input').val(descripcion);}",
						],

		            ]
		        ],
		        [
	                'name' => 'descripcion',
	                'enableError' => true,
	                'options' => [
	                	'readonly' => true,
	                ],
	                          	
	                'title' => 'Descripción del medicamento',
	            ],    
		        [
		            'name'  => 'RM_FECVTO',
		            'type'  => \kartik\date\DatePicker::className(),
		            'title' => 'Fecha Vto',
		            'value' => function($data) {
		                return $data['RM_FECVTO'];
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

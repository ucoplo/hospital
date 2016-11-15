<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\datecontrol\DateControl;
use yii\widgets\Pjax;
use yii\grid\GridView;
use unclead\widgets\MultipleInput;
use yii\helpers\ArrayHelper;
use deposito_central\models\ArticGral;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Planilla_entrega */
/* @var $form yii\widgets\ActiveForm */
//= $form->errorSummary($model); 
?>

<div class="planilla-entrega-form">

    
    <?php $action = $model->isNewRecord ?[ 'id' => 'create-planilla-entrega-form', 'action' => ['planilla_entrega/create_sin_pedido']]:null;
      $form = ActiveForm::begin($action) ?>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'PE_NROREM')->textInput(['maxlength' => true,'readonly' => 'true']) ?>
        </div>
       
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'PE_SERSOL')->widget(kartik\select2\Select2::classname(), [
                'data' => $model->listaServicios,
                'options' => ['placeholder' => 'Seleccione Servicio ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
        </div>
    </div>
     <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'PE_FECHA')
            ->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'autoWidget' => false,
                'options' => ['readonly' => 'true'],
            ]);?>
          
        </div>
        <div class="col-md-3">

            <?= $form->field($model, 'PE_HORA')->textInput(['readonly' => 'true']) ?>
        </div>
        
    </div>
     <div class="row">
        <div class="col-md-3">
           <?= $form->field($model, 'PE_DEPOSITO')->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito']);?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'PE_CODOPE')->textInput(['readonly' => 'true']) ?>
        </div>
        <div class="col-md-9">
           <?= Html::label('', 'personal') ?>
            <?php $personal = ((isset($model->PE_CODOPE))?$model->operador->LE_APENOM:'');?>
            <?= Html::input('text', 'personal',$personal, ['class'=>'form-control','readonly'=>true]) ?> 
           
        </div>
    </div>   
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'PE_ENFERM')->widget(kartik\select2\Select2::classname(), [
                'data' => $model->listaEnfermeros,
                'options' => ['placeholder' => 'Seleccione Personal ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
        </div>
    </div>   
       
       
    <?= $form->field($model, 'renglones')->widget(MultipleInput::className(), [
                //'limit' => 4,
                'addButtonPosition' => MultipleInput::POS_HEADER,
                'enableGuessTitle'  => true,

                'columns' => [
                     [
                        'name'  => 'PR_CODART',
                        
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
                                "select2:select" => "function(name) {
                                                        if (!codigo_unico($(this))==true)
                                                        {
                                                          descripcion = name.params.data.text.substring(5); 
                                                          $(this).closest('td').next().find('input').val(descripcion);
                                                          cargar_vencimiento($(this));
                                                          
                                                        }
                                                        else{
                                                            krajeeDialog.alert('No puede repetirse el medicamento');
                                                            $(this).val('').trigger('change');
                                                            $(this).closest('td').next().find('input').val('');
                                                            $(this).closest('td').next().next().find('input').val('');
                                                        }
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
                    [
                        'name'  => 'PR_FECVTO',
                        //'type'  => \kartik\date\DatePicker::className(),
                        'title' => 'Fecha Vto',
                        'value' => function($data) {
                            return $data['PR_FECVTO'];
                        },
                        
                        'options' => [
                        
                        'readonly' => true,
                        
                        ],
                        'headerOptions' => [
                            'style' => 'width: 150px;',
                            'class' => 'day-css-class'
                        ]
                    ],
                    [
                        'name'  => 'PR_CANTID',
                        //'enableError' => true,
                        'title' => 'Cantidad retirada',
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            
                        ],
                        'options' => ['class' => 'money'],
                        
                        
                    ],
                    
                    
                ]
             ]);
            ?>
 
    
    <div class="form-group text-right" id="wrp_guardar">
                <?= Html::submitButton('Guardar', ['name' => 'btnguardar',
                                                    'id' => 'btnguardar',
                                                    'class' => 'btn btn-success',
                                                    'data' => [
                        'confirm' => '¿Confirma el Vale de Depósito Central?',
                        'method' => 'post',
                    ],]) ?>
    </div>


    <?php ActiveForm::end(); ?>
</div>

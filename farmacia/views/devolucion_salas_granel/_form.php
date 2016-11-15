<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use unclead\widgets\MultipleInput;
use yii\helpers\ArrayHelper;
use farmacia\models\ArticGral;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_salas_granel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="devolucion-salas-granel-form">

    <?php $action = $model->isNewRecord ?[ 'id' => 'create-devolucion-farmacia-form', 'action' => ['devolucion_salas_granel/create']]:null;
      $form = ActiveForm::begin($action) ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'DE_NRODEVOL')->textInput(['maxlength' => true,'readonly' => 'true']) ?>
        </div>
       
    </div>
    <?= $form->field($model, 'DE_SERSOL')->hiddenInput()->label(false);?>
    <div class="row">
        <div class="col-md-6">
            
            <?= Html::label('Servicio Solicitante', 'servicio') ?>
            <?php $servicio = ((isset($model->DE_SERSOL))?$model->servicio->SE_DESCRI:'');?>
            <?= Html::input('text', 'servicio',$servicio, ['class'=>'form-control','readonly'=>true]) ?> 
            <div class="help-block"></div>
        </div>
        <div class="col-md-3">
          <?= $form->field($model, 'DE_NUMREMOR')->textInput(['readonly' => 'true']) ?>
        </div>  
    </div>
     <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'DE_FECHA')
            ->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'autoWidget' => false,
                'options' => ['readonly' => 'true'],
            ]);?>
          
        </div>
        <div class="col-md-3">

            <?= $form->field($model, 'DE_HORA')->textInput(['readonly' => 'true']) ?>
        </div>
        
    </div>
     <div class="row">
        <div class="col-md-3">
            
             <?= $form->field($model, 'DE_DEPOSITO')->hiddenInput()->label(false);?>
            <?= Html::label('Depósito', 'deposito') ?>
            <?php $deposito = ((isset($model->DE_DEPOSITO))?$model->deposito->DE_DESCR:'');?>
            <?= Html::input('text', 'deposito',$deposito, ['class'=>'form-control','readonly'=>true]) ?> 
            <div class="help-block"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'DE_CODOPE')->textInput(['readonly' => 'true']) ?>
        </div>
        <div class="col-md-9">
           <?= Html::label('', 'personal') ?>
            <?php $personal = ((isset($model->DE_CODOPE))?$model->operador->LE_APENOM:'');?>
            <?= Html::input('text', 'personal',$personal, ['class'=>'form-control','readonly'=>true]) ?> 
           
        </div>
    </div>   
  
    <?= $form->field($model, 'DE_ENFERM')->hiddenInput()->label(false);?>
   
     
       
    <?= $form->field($model, 'renglones')->widget(MultipleInput::className(), [
                //'limit' => 4,
                'addButtonPosition' => MultipleInput::POS_HEADER,
                'enableGuessTitle'  => true,

                'columns' => [
                     [
                        'name'  => 'DF_CODMON',
                        
                        'title' => 'Cod. Medicamento',
                        'type' => kartik\select2\Select2::classname(),
                        'options' => [
                            'data' => ArrayHelper::map($model->monodrogasRemito, 'AG_CODIGO', 
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
                        'name'  => 'DF_FECVTO',
                        //'type'  => \kartik\date\DatePicker::className(),
                        'title' => 'Fecha Vto',
                        'value' => function($data) {
                            return $data['DF_FECVTO'];
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
                        'name'  => 'DF_CANTID',
                        //'enableError' => true,
                        'title' => 'Cantidad devuelta',
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
                        'confirm' => '¿Confirma la Devolución?',
                        'method' => 'post',
                    ],]) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>

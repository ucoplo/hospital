<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\datecontrol\DateControl;
use yii\widgets\Pjax;
use yii\grid\GridView;
use unclead\widgets\MultipleInput;
use yii\helpers\ArrayHelper;
use farmacia\models\ArticGral;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Consumo_medicamentos_pacientes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="consumo-medicamentos-pacientes-form">

    
    
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">,
        <div class="col-md-3">
            <?= $form->field($model, 'CM_NROREM')->textInput(['maxlength' => true,'readonly' => 'true']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'CM_SERSOL')->hiddenInput()->label(false);?>
            <?= Html::label('Servicio Solicitante', 'servicio') ?>
            <?php $servicio = ((isset($model->CM_SERSOL))?$model->servicio->SE_DESCRI:'');?>
            <?= Html::input('text', 'servicio',$servicio, ['class'=>'form-control','readonly'=>true]) ?> 
            <div class="help-block"></div>
        </div>
    </div>
     <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'CM_FECHA')
            ->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'autoWidget' => false,
                'options' => ['readonly' => 'true'],
            ]);?>
          
        </div>
        <div class="col-md-3">

            <?= $form->field($model, 'CM_HORA')->textInput(['readonly' => 'true']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'CM_NROVAL')->textInput(['readonly' => 'true']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            
             <?= $form->field($model, 'CM_DEPOSITO')->hiddenInput()->label(false);?>
            <?= Html::label('Depósito', 'deposito') ?>
            <?php $deposito = ((isset($model->CM_DEPOSITO))?$model->deposito->DE_DESCR:'');?>
            <?= Html::input('text', 'deposito',$deposito, ['class'=>'form-control','readonly'=>true]) ?> 
            <div class="help-block"></div>
        </div>
    </div>

       <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'CM_CODOPE')->textInput(['readonly' => 'true']) ?>
        </div>
        <div class="col-md-9">
           <?= Html::label('', 'personal') ?>
            <?php $personal = ((isset($model->CM_CODOPE))?$model->operador->LE_APENOM:'');?>
            <?= Html::input('text', 'personal',$personal, ['class'=>'form-control','readonly'=>true]) ?> 
           
        </div>
    </div>    
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'CM_MEDICO')->textInput(['readonly' => 'true']) ?>
         </div>
        <div class="col-md-9"  id='wrp_medico'>
            <?= Html::label('', 'medico') ?>
            <?php $medico = ((isset($model->CM_MEDICO))?$model->medico->LE_APENOM:'');?>
            <?= Html::input('text', 'medico',$medico, ['class'=>'form-control','readonly'=>true]) ?> 
            
        </div>
    </div>    
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'CM_HISCLI')->textInput(['readonly' => 'true']) ?>
        </div>
        <div class="col-md-9"  id='wrp_paciente'>   
            <?= Html::label('', 'paciente') ?>
            <?php $paciente = ((isset($model->CM_HISCLI))?$model->paciente->PA_APENOM:'');?>
            <?= Html::input('text', 'paciente',$paciente, ['class'=>'form-control','readonly'=>true]) ?> 
                   
        </div>
    </div>  
  

    <?= $form->field($model, 'renglones')->widget(MultipleInput::className(), [
                //'limit' => 4,
                'addButtonPosition' => MultipleInput::POS_HEADER,
                'enableGuessTitle'  => true,

                'columns' => [
                     [
                        'name'  => 'VA_CODMON',
                        
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
                        'name'  => 'VA_FECVTO',
                        //'type'  => \kartik\date\DatePicker::className(),
                        'title' => 'Fecha Vto',
                        'value' => function($data) {
                            return $data['VA_FECVTO'];
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
                        'name'  => 'VA_CANTID',
                        'enableError' => true,
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
                        'confirm' => '¿Confirma el Vale de Farmacia?',
                        'method' => 'post',
                    ],]) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>

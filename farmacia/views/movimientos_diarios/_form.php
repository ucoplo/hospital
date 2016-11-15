<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use unclead\widgets\MultipleInput;
use yii\helpers\ArrayHelper;
use farmacia\models\ArticGral;
use farmacia\models\Movimientos_tipos;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Movimientos_diarios */
/* @var $form yii\widgets\ActiveForm */
?>

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
                   
                    [
                        'name' => 'MD_CODMOV',
                        'title' => 'Tipo',
                        'type' =>  kartik\select2\Select2::classname(),
                        
                        'options' => [
                            'data' => ArrayHelper::map($model->listaTipos, 'MS_COD', 
                                            function($model, $defaultValue) {
                                                return $model['MS_COD'].'-'.$model['MS_NOM'];
                                            }),
                        ],  
                    ],
                    
                     [
                        'name'  => 'MD_FECVEN',
                        'type'  => \kartik\date\DatePicker::className(),
                        'title' => 'Fecha Vto',
                        'value' => function($data) {
                            return $data['MD_FECVEN'];
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
                        'name'  => 'MD_CANT',
                        //'enableError' => true,
                        'title' => 'Cantidad',
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
                        'confirm' => '¿Confirma los Movimientos?',
                        'method' => 'post',
                    ],]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

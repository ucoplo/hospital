<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use unclead\widgets\MultipleInput;
use yii\helpers\ArrayHelper;
use deposito_central\models\ArticGral;
use deposito_central\models\Movimientos_tipos;
use deposito_central\models\Movimientos_diarios;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Movimientos_diarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="movimientos-diarios-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
            <div class="col-md-3">
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
        <div class="col-md-3">
            
             <?= $form->field($model, 'DM_DEPOSITO')->hiddenInput()->label(false);?>
            <?= Html::label('Depósito', 'deposito') ?>
            <?php $deposito = ((isset($model->DM_DEPOSITO))?$model->deposito->DE_DESCR:'');?>
            <?= Html::input('text', 'deposito',$deposito, ['class'=>'form-control','readonly'=>true]) ?> 
            <div class="help-block"></div>
        </div>
    </div>

     <?= $form->field($model, 'renglones')->widget(MultipleInput::className(), [
                //'limit' => 4,
                'addButtonPosition' => MultipleInput::POS_HEADER,
                'enableGuessTitle'  => true,
                'allowEmptyList' => true,
                // 'rowOptions' => function ($model, $index, $context) {
                //             $tipo = Movimientos_tipos::findOne($model['DM_CODMOV']);
                            
                //             if($tipo['DM_SIGNO']<0) {
                //                 return ['class'=>'row_readonly'];
                //             }
                //         },
                'columns' => [
                    
                     [
                        'name'  => 'DM_CODART',
                        
                        'title' => 'Artículo',
                        'type' => kartik\select2\Select2::classname(),
                        'options' => function($data) use ($model){
                            $deposito = $model->DM_DEPOSITO;
                            $url_busqueda_articulos = \yii\helpers\Url::to(['pedido_adquisicion/buscar-articulos','deposito'=>$deposito]);
                            if (isset($data['DM_CODMOV'])){
                                $tipo = Movimientos_tipos::findOne($data['DM_CODMOV']);
                                $habilitado = ($tipo['DM_SIGNO']>0);
                            }
                            else{
                                $habilitado = true;
                            }
                            return 
                            [
                                'data' => (!empty($data['DM_CODART']))?[ "{$data['DM_CODART']}" => "[{$data['DM_CODART']}] ".$data['descripcion']]:[],

                                'pluginOptions' => [
                                    'ajax' => [
                                        'url' => $url_busqueda_articulos,
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) {
                                          return {q:params.term};
                                        }')
                                    ],
                                    'disabled' => !$habilitado,
                                    'enableEmpty' => true,
                                    'minimumInputLength' => 1,
                                    'language' => 'es',
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(articulo) { return articulo.text; }'),
                                    'templateSelection' => new JsExpression('function (articulo) { return articulo.text; }'),
                                ],
                                'pluginEvents' => [
                                    "select2:select" => "function(name) {
                                                            descripcion = name.params.data.text.substring(6); 
                                                            $(this).closest('td').next().find('input').val(descripcion);
                                                        }",
                                ],
                            ];
                        },
                        
                    ],
                    // [
                    //     'name' => 'descripcion',
                    //     'enableError' => true,
                    //     'value' => function($data) {
                    //             return $data['descripcion'];
                    //         },
                    //     'options' => [
                    //         'readonly' => true,
                    //     ],
                    //     'title' => 'Descripción del medicamento',
                    // ],
                   
                    [
                        'name' => 'DM_CODMOV',
                        'title' => 'Tipo',
                        'type' =>  kartik\select2\Select2::classname(),
                        
                        'options' => function($model){
                            if (isset($model['DM_CODMOV'])){
                                $tipo = Movimientos_tipos::findOne($model['DM_CODMOV']);
                                $habilitado = ($tipo['DM_SIGNO']>0);
                            }
                            else{
                                $habilitado = true;
                            }
                            return [
                            'data' => ArrayHelper::map(Movimientos_diarios::listaTipos($habilitado), 'DM_COD', 
                                            function($model, $defaultValue) {
                                                return $model['DM_COD'].'-'.$model['DM_NOM'];
                                            }),
                            'options' => ['readonly'=> $habilitado],
                            'pluginOptions' => ['disabled' => !$habilitado],
                            ];

                        },
                          
                    ],
                    
                     [
                        'name'  => 'DM_FECVTO',
                        'type'  => \kartik\date\DatePicker::className(),
                        'title' => 'Fecha Vto',
                        'value' => function($data) {
                            return $data['DM_FECVTO'];
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
                        'name'  => 'DM_CANT',
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

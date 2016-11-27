<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use deposito_central\models\ArticGral;
use unclead\widgets\MultipleInput;
use yii\web\JsExpression;
use kartik\dropdown\DropdownX;
use deposito_central\assets\Devolucion_ProveedorAsset;
use deposito_central\models\Proveedores;
/* @var $this yii\web\View */
/* @var $model deposito_central\models\Devolucion_proveedor */
/* @var $form yii\widgets\ActiveForm */

Devolucion_ProveedorAsset::register($this);
?>

<div class="devolucion-proveedor-form">

 
    <?php $form = ActiveForm::begin([
        'id' => 'form-devolucion',
        //'enableAjaxValidation' => true,
        //'validationUrl' => 'index.php?r=devolucion_proveedor/validate-devolucion',    
        ]);?>
     <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'DD_FECHA')->widget(DateControl::classname(), [
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
                <?= $form->field($model, 'DD_HORA')->textInput([ 'readonly' => 'true']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'DD_DEPOSITO')->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito']);?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?php  $url_busqueda_proveedores = \yii\helpers\Url::to(['reportes/buscar-proveedores']);
                ?>
                 <?= $form->field($model, 'DD_PROVE')->widget(Select2::classname(), [
                    'options' => ['placeholder' => 'Seleccione Proveedor ...'],

                    'pluginOptions' => [
                        'ajax' => [
                            'url' => $url_busqueda_proveedores,
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) {
                              return {q:params.term};
                            }')
                        ],
                        'allowClear' => true,
                        'enableEmpty' => true,
                        'minimumInputLength' => 1,
                        'language' => 'es',
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(articulo) { return articulo.text; }'),
                        'templateSelection' => new JsExpression('function (articulo) { return articulo.text; }'),
                    ],
                 
                ]);?>
               
               
            </div>

        </div>    
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'DD_COMENTARIO')->textarea(['rows' => 3]) ?>
            </div>
           
        </div>        
        
        <?= $form->field($model, 'renglones')->widget(MultipleInput::className(), [
            //'limit' => 4,
            'addButtonPosition' => MultipleInput::POS_HEADER,
            
            'columns' => [
                [
                  'name'  => 'DP_CODART',
                    
                    'title' => 'Cod. Medicamento',
                    'type' => kartik\select2\Select2::classname(),
                    'options' => function($data) use ($model){
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
                                            deposito_id = $("#devolucion_proveedor-dd_deposito").val();
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
                                        krajeeDialog.alert('No puede repetirse el artículo');
                                    }
                                    else{
                                        descripcion = result.params.data.text.substring(6); 
                                        $(this).closest('td').next().find('input').val(descripcion);
                                        cargar_vencimientos($(this));
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
                    'value' => function($data) {
                            return $data['descripcion'];
                        },
                    
                    'title' => 'Descripción del medicamento',
                ],   
                 [
                    'name'  => 'DP_FECVTO',
                    'type'  => 'dropDownList',
                    'title' => 'Fecha Vto',
                    //'defaultValue' => null,
                    'items' => function($data) {

                        if (isset($data['DP_FECVTO']) && !empty($data['DP_FECVTO'])){
                            if (isset($data['vencimientos']))
                                $vencimientos = $data['vencimientos'];
                            else
                                $vencimientos = [];
                            
                             $items= [];
                             $insertada = false;
                             for ($i=0; $i < sizeof($vencimientos); $i++) { 
                                 $fecha = $vencimientos[$i]['TV_FECVEN'];
                                 $items[$fecha] = Yii::$app->formatter->asDate($fecha,'php:d-m-Y');
                                 if ($fecha === $data['DP_FECVTO']){
                                    $insertada = true;
                                 }
                             }

                             if (!$insertada){
                                $items[$data['DP_FECVTO']] = Yii::$app->formatter->asDate($data['DP_FECVTO'],'php:d-m-Y');
                             }


                        }else
                        { $items = [];}
                                

                            return $items;
                           
                        },
                    'headerOptions' => [
                        'style' => 'width: 200px;',
                        'class' => 'day-css-class'
                    ]
                ], 
               
               
                 [
                    'name'  => 'DP_CANTID',
                    'enableError' => true,
                   
                    'title' => 'Cantidad',
                    'headerOptions' => [
                        'style' => 'width: 200px;',
                        
                    ]
                    
                    
                ],
                
            ]
         ]);
        ?>

    

    

         <div class="form-group text-right">
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

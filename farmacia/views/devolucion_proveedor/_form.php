<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use farmacia\models\Labo;
use farmacia\models\ArticGral;
use unclead\widgets\MultipleInput;
use yii\web\JsExpression;
use kartik\dropdown\DropdownX;
use farmacia\assets\Devolucion_ProveedorAsset;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_proveedor */
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
                <?= $form->field($model, 'DE_FECHA')->widget(DateControl::classname(), [
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
                <?= $form->field($model, 'DE_HORA')->textInput([ 'readonly' => 'true']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'DE_DEPOSITO')->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito']);?>
            </div>
        </div>

        <div class="row">
           <div class="col-md-3">

                <?= $form->field($model, 'DE_DESTINO')->radioList(array('D' => 'Depósito Central', 'E' => 'Externo')); ?>
            </div>
            <div class="col-md-6">

               
                <?= $form->field($model, 'DE_PROVE')->widget(Select2::classname(), [
                    'data' =>  ArrayHelper::map(Labo::find()->all(), 'LA_CODIGO', 
                                        function($model, $defaultValue) {
                                            return $model['LA_CODIGO'].'-'.$model['LA_NOMBRE'];
                                        }),
                    'options' => ['placeholder' => 'Seleccione Proveedor ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>
            </div>

        </div>    
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'DE_COMENTARIO')->textarea(['rows' => 3]) ?>
            </div>
           
        </div>        
        
        <?= $form->field($model, 'renglones')->widget(MultipleInput::className(), [
            //'limit' => 4,
            'addButtonPosition' => MultipleInput::POS_HEADER,
            
            'columns' => [
                [
                  'name'  => 'DP_CODMON',
                    
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
                                                                  $(this).closest('td').next().find('input').val(descripcion);
                                                                    cargar_vencimientos($(this));}",
                        ],
                    ]

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

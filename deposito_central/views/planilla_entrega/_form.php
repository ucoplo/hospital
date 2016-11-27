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

    
    <?php $action = $model->isNewRecord ?[ 'id' => 'create-planilla-entrega-form', 'action' => ['planilla_entrega/create']]:null;
      $form = ActiveForm::begin($action) ?>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'PE_NROREM')->textInput(['maxlength' => true,'readonly' => 'true']) ?>
        </div>
       
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'PE_SERSOL')->hiddenInput()->label(false);?>
            <?= Html::label('Servicio Solicitante', 'servicio') ?>
            <?php $servicio = ((isset($model->PE_SERSOL))?$model->servicio->SE_DESCRI:'');?>
            <?= Html::input('text', 'servicio',$servicio, ['class'=>'form-control','readonly'=>true]) ?> 
            <div class="help-block"></div>
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
            
             <?= $form->field($model, 'PE_DEPOSITO')->hiddenInput()->label(false);?>
            <?= Html::label('Depósito', 'deposito') ?>
            <?php $deposito = ((isset($model->PE_DEPOSITO))?$model->deposito->DE_DESCR:'');?>
            <?= Html::input('text', 'deposito',$deposito, ['class'=>'form-control','readonly'=>true]) ?> 
            <div class="help-block"></div>
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
  
    <?= $form->field($model, 'PE_ENFERM')->hiddenInput()->label(false);?>
    <?= $form->field($model, 'PE_NUMVALE')->hiddenInput()->label(false);?>

       
    <?= $form->field($model, 'renglones')->widget(MultipleInput::className(), [
                //'limit' => 4,
                'addButtonPosition' => MultipleInput::POS_HEADER,
                'enableGuessTitle'  => true,

                'columns' => [
                     [
                        'name'  => 'PR_CODART',
                        
                        'title' => 'Cod. Medicamento',
                        'type' => kartik\select2\Select2::classname(),
                        'options' => function($data) use ($model){
                            $url_busqueda_articulos = \yii\helpers\Url::to(['reportes/buscar-articulos']);
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
                                            deposito_id = $("#planilla_entrega-pe_deposito").val();
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
                                "select2:select" => "function(name) {
                                                        if (!codigo_unico($(this))==true)
                                                        {
                                                          descripcion = name.params.data.text.substring(6); 
                                                          $(this).closest('td').next().find('input').val(descripcion);
                                                          cargar_vencimiento($(this));
                                                          
                                                        }
                                                        else{
                                                            krajeeDialog.alert('No puede repetirse el artículo');
                                                            $(this).val('').trigger('change');
                                                            $(this).closest('td').next().find('input').val('');
                                                            $(this).closest('td').next().next().find('input').val('');
                                                        }
                                                    }",

                                ],
                            ];
                        },
                        
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

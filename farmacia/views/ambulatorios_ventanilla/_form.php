<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use unclead\widgets\MultipleInput;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use farmacia\models\ArticGral;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use yii\bootstrap\Modal;
use farmacia\assets\Vale_AmbulatorioAsset;
use kartik\typeahead\Typeahead;

use kartik\dialog\Dialog;
/* @var $this yii\web\View */
/* @var $model farmacia\models\Ambulatorios_ventanilla */
/* @var $form yii\widgets\ActiveForm */

echo Dialog::widget();

Vale_AmbulatorioAsset::register($this);

if (count($model->errors)!==0){
    $displayPaciente = "style='display:none;'";
    $displayVale = "style='display:block;'";
}else{
   $displayVale = "style='display:none;'";
    $displayPaciente = "style='display:block;'"; 
}
?>

<div class="ambulatorios-ventanilla-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'AM_NUMVALE', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->textInput(['readonly' => 'true']) ?>
       
        </div>

        <div class="col-md-4">
        <?= $form->field($model, 'AM_FECHA', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'autoWidget' => false,
                'options' => ['readonly' => 'true'],
            ]);?>
        </div>
        <div class="col-md-4">
        <?= $form->field($model, 'AM_HORA',
         ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
         ->textInput(['readonly' => 'true']) ?>
        </div>
    </div>

    <div class="row" id="campoPaciente" <?=$displayVale?>>
        <div class="col-md-6">
         
                <?php $datos = $paciente->PA_HISCLI.'-'.$paciente->PA_APENOM;?>
                <?= Html::label('Paciente', 'paciente') ?>
                <?= Html::input('text', 'paciente', $datos, ['class'=>'form-control','readonly'=>true,'id'=>'campoDatosPaciente']) ?> 
            
         
        </div>
    </div>

    <hr />
    <div id="datosPaciente" <?=$displayPaciente?>>
         <h3>PACIENTE</h3>
             <div class="row">
                <div class="col-md-2">
                    <!-- 'fecha' => $fecha, 'hora' => $hora -->
                    <?= Html::a('Paciente Nuevo', Url::toRoute(['paciente/create']), ['class' => 'btn btn-success']) ?>
                    
                </div>
                
                <div class="col-md-8 col-md-offset-1">
                    <?=Typeahead::widget([
                            'id' => 'search-paciente',
                            'name' => 'search',
                            'options' => ['placeholder' => 'Busque por HC, DNI, Apellido y/o Nombres ...'],
                            'pluginOptions' => ['highlight'=>true],
                            'dataset' => [
                                [
                                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                                    'display' => 'value',
                                    'remote' => [
                                        'url' => Url::to(['paciente/query']) . '&q=%Q',
                                        'wildcard' => '%Q'
                                    ],
                                    'limit' => 5
                                ]
                            ],
                            'pluginEvents' => [
                                'typeahead:selected' => 'function(e,datum) {
                                    cargar_datos_paciente(e,datum);
                                }',
                                'typeahead:autocompleted' => 'function(e,datum) {
                                    cargar_datos_paciente(e,datum);
                                }',
                            ],
                        ]);
                    ?>
                </div>
            </div>
        
        
        <div class="row">
            <div class="col-md-3">
            <?= $form->field($model, 'AM_HISCLI')->textInput(['readonly' => 'true']) ?>
            </div>

            
        </div>
        <div class="row">
            <div class="col-md-6">
            <?= Html::label('Apellido', 'apellido') ?>
            <?= Html::input('text', 'apellido', $paciente->PA_APELLIDO, ['id'=>'paciente-pa_apellido','class'=>'form-control','readonly'=>true]) ?>    
            
            </div>
            <div class="col-md-6">
            <?= Html::label('Nombres', 'nombre') ?>
            <?= Html::input('text', 'nombre', $paciente->PA_NOMBRE, ['id'=>'paciente-pa_nombre','class'=>'form-control','readonly'=>true]) ?>    
            </div>

        </div>
        <div class="row">
            <div class="col-md-3">
            <?= $form->field($paciente, 'PA_TIPDOC')
                ->textInput(['readonly' => 'true', 'maxlength' => true]) ?>
            </div>

            <div class="col-md-3">
            <?= $form->field($paciente, 'PA_NUMDOC', 
            ['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])
            ->textInput(['readonly' => 'true', 'maxlength' => true]) ?>
            </div>
             <div class="col-md-3">
            <?= Html::label('Edad', 'edad') ?>
            <?= Html::input('text', 'edad', $paciente->edad, ['class'=>'form-control','readonly'=>true,'id' => 'paciente-pa_edad']) ?>    
            </div>
             <div class="col-md-3">
                <?= Html::label('Sexo', 'sexo') ?>
                <?= Html::input('text', 'sexo', $paciente->sexo, ['class'=>'form-control','readonly'=>true,'id' => 'paciente-pa_sexo']) ?>    

            
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
            <?= $form->field($paciente, 'PA_LOCNAC', 
                ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
                ->textInput(['readonly' => 'true', 'maxlength' => true]) ?>
            </div>
             <div class="col-md-9">

                <?= Html::label('Descripción Localidad Natal', 'descripcion_loc') ?>
                <?= Html::input('text', 'descripcion_loc', $paciente->localidadNacimientoDescripcion, ['class'=>'form-control','readonly'=>true,'id' => 'paciente-pa_locnac_descripcion']) ?>    
            
            </div>
        </div>
        
        <?php
            Modal::begin([
                'header' => 'Datos Adicionales Paciente',
                'id' => 'modalMasDatos',
                'size' => 'modal-lg',
                //keeps from closing modal with esc key or by clicking out of the modal.
                // user must click cancel or X to close
                'clientOptions' => ['backdrop' => 'static'],
                'toggleButton' => ['label' => 'Mas datos','class' => 'btn btn-primary'],
            ]);
            echo $this->render('_masdatos', [
                 'paciente' => $paciente,
            ]) ;
            Modal::end();
        ?>

       
         <hr />

        

        <div class="row">
            <div class="col-md-3">
            <?= $form->field($paciente, 'PA_CODOS')
                ->textInput(['readonly' => 'true', 'maxlength' => true]) ?>
            </div>
            
            <div class="col-md-6">
            <?= Html::label('Descripción Obra Social', 'obra') ?>
             <?= Html::input('text', 'obra', $paciente->obraSocialDescripcion, ['class'=>'form-control','readonly'=>true,'id' => 'paciente-pa_codos_descricpion']) ?>     
              
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            <?= $form->field($paciente, 'PA_NIVEL', 
                ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
                ->textInput(['readonly' => 'true', 'maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
              <?= Html::label('Vencimiento de la Categorización', 'venniv') ?>
             <?= Html::input('text', 'venniv', $paciente->vencimientoNivel, ['class'=>'form-control','readonly'=>true,'id' => 'paciente-pa_venniv']) ?>     

            
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <?= $form->field($paciente, 'PA_OBSERV')->textarea(['rows' => 3,'readonly' => 'true']) ?>
            
            </div>
                    </div>

        <div class="text-right">
            <?= Html::button('Siguiente',['class' => 'btn btn-primary','id'=>'btn_siguiente_vale'])?>
        </div>
    </div> <!-- Datos Paciente-->

    <div id="datosVale" <?=$displayVale?>>
    
         <?= $form->field($model, 'AM_ENTIDER')->widget(Select2::classname(), [
            'data' => $model->listaEntidades,
            'options' => ['placeholder' => 'Seleccione Entidad ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);?>

         <?= $form->field($model, 'AM_MEDICO')->widget(Select2::classname(), [
            'data' => $model->listaMedicos,
            'options' => ['placeholder' => 'Seleccione Médico ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);?>

        <?= $form->field($model, 'AM_PROG')->dropDownList($model->listaProgramas, ['prompt' => 'Seleccione Programa' ]);?>

        <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'AM_DEPOSITO')->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito']);?>
                </div>
            </div>

        <hr />
        <div class="row">
            <div class="col-md-12">
                <?= Html::button('Historial de retiros', ['value' => Url::to(['historialretiros', 'AM_HISCLI' => $model->AM_HISCLI]), 'title' => 'Historial', 'id' => 'btn_historial_retiros','class' => 'showModalButton btn btn-primary']); ?>
                   
               
                <?= Html::button('Recetas', ['value' => Url::to(['recetaspaciente', 'AM_HISCLI' => $model->AM_HISCLI]), 'title' => 'Recetas', 'id' => 'btn_recetas','class' => 'showModalButton btn btn-primary']); ?>
              </div>   
        </div>
         <hr />
     
       
            <?= $form->field($model, 'renglones')->widget(MultipleInput::className(), [
                //'limit' => 4,
                'addButtonPosition' => MultipleInput::POS_HEADER,
                'enableGuessTitle'  => true,
                'allowEmptyList' => true,
                'columns' => [
                     [
                        'name'  => 'AM_CODMON',
                        
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
                                                          cargar_cantidad_acumulada($(this));
                                                        }
                                                        else{
                                                            krajeeDialog.alert('No puede repetirse el medicamento');
                                                            $(this).val('').trigger('change');
                                                            $(this).closest('td').next().find('input').val('');
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
                        'name'  => 'AM_FECVTO',
                        //'type'  => \kartik\date\DatePicker::className(),
                        'title' => 'Fecha Vto',
                        'value' => function($data) {
                            return $data['AM_FECVTO'];
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
                        'name'  => 'AM_CANTPED',
                        
                       
                        'title' => 'Cantidad Pedida',
                        'headerOptions' => [
                            'style' => 'width: 200px;',
                            
                        ],
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            
                        ],
                         'options' => ['class' => 'money'],
                        
                        
                    ],

                      [
                        'name'  => 'AM_CANTENT',
                        
                        'title' => 'Cantidad retirada',
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            
                        ],
                        'options' => ['class' => 'money'],
                        
                        
                    ],
                     [
                        'name'  => 'cant_acumulada',
                        
                        'value' => function($data) {
                            return $data['cant_acumulada'];
                        },
                         'options' => [
                            'readonly' => true,
                        ],
                        'title' => 'Cantidad acumulada',
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            
                        ],
                       
                        
                    ],
                    
                ]
             ]);
            ?>
             <div class="form-group text-right">
                <?= Html::submitButton('Guardar', ['name' => 'btnguardar',
                                                    'id' => 'btnguardar',
                                                    'class' => 'btn btn-success',
                                                    'data' => [
                        'confirm' => '¿Confirma el Vale de Ventanilla?',
                        'method' => 'post',
                    ],]) ?>
            </div>
    </div>      
        

    <?php ActiveForm::end(); ?>

</div>


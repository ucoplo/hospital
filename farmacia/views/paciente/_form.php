<?php

use farmacia\models\TipoDocumento;

use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use kartik\typeahead\Typeahead;
use kartik\datecontrol\DateControl;
use farmacia\assets\Vale_AmbulatorioAsset;

use kartik\dialog\Dialog;

echo Dialog::widget();

Vale_AmbulatorioAsset::register($this);

/* @var $this yii\web\View */
/* @var $model smu\models\Paciente */
/* @var $form yii\widgets\ActiveForm */



?>

<div class="paciente-form">

    <?php 
    $form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data'
            ],
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-md-2',
                    'wrapper' => 'col-md-10'
                ]
            ],
            'layout' => 'horizontal'
        ]);
     ?>

    <div class="row">
        <div class="col-md-4">
            <?php 
                $tiposDeDocumento = TipoDocumento::listaTiposDocumento();
            ?>
            <?= $form->field($model, 'PA_TIPDOC', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->dropDownList($tiposDeDocumento, ['onchange' => 'buscarPorTipoNumeroDocumento();']); ?>

        <?= $form->field($model, 'PA_NUMDOC', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->widget(MaskedInput::className(), ['mask' => 'A{0,2}9{1,12}', 'options' => ['class' => 'form-control', 'onchange' => 'buscarPorTipoNumeroDocumento();']]) ?>
        </div>

        <div class="col-md-4">
        <?= $form->field($model, 'PA_HISCLI', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->textInput(['readonly' => 'true', 'maxlength' => true]) ?>

        <?= $form->field($model, 'PA_UBIC', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->textInput(['readonly' => 'true', 'maxlength' => true]) ?>
        </div>

        <div class="col-md-4">
        <?= $form->field($model, 'PA_ORIGEN', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->textInput(['readonly' => 'true', 'maxlength' => true]) ?>
        </div>
    </div>

    <div id="sugerencias-tipo-nro-doc"></div>

    <hr />

    <div class="row">
        <div class="col-md-8">
        <?= $form->field($model, 'PA_APENOM', 
            ['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-9']])
            ->textInput(['readonly' => 'true', 'maxlength' => true]) ?>

        <?= $form->field($model, 'PA_APELLIDO', 
            ['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-9']])
            ->textInput(['maxlength' => true, 'onchange' => 'armarNombreApellido();']) ?>

        <?= $form->field($model, 'PA_NOMBRE', 
            ['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-9']])
            ->textInput(['maxlength' => true, 'onchange' => 'armarNombreApellido();']) ?>

        <?= $form->field($model, 'PA_NOMELEG',
            ['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-9']])
            ->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-4">
        <?= $form->field($model, 'PA_FECNAC', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'removeButton' => false,
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'endDate' => '0',
                        ]
                ],
            ]); ?>

        <?= $form->field($model, 'PA_SEXO', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->dropDownList([ 'F' => 'Femenino', 'M' => 'Masculino'], ['prompt' => 'Seleccione']) ?>
        </div>
    </div>

    <hr />

    <?= Html::activeHiddenInput($model, 'PA_NACION') ?>
    <?= $form->field($model, 'nacionalidadDescripcion', 
    ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
    ->widget(
        TypeAhead::className(), [
        'name' => 'nacion',
        'options' => ['placeholder' => 'Ingrese Nacionalidad ...', 'class' => 'form-control', 'id' => 'paciente-nacion'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['nacionalidad/query']) . '&q=%QUERY',
                    'wildcard' => '%QUERY'
                ],
                'limit' => 10
            ]
        ],
        'pluginEvents' => [
            'typeahead:selected' => 'function(e,datum) { setPacienteNacionalidad(e,datum); }',
            'typeahead:autocompleted' => 'function(e,datum) { setPacienteNacionalidad(e,datum); }',
        ]
    ]); ?>

    <?= Html::activeHiddenInput($model, 'PA_CODPAIS') ?>
    <?= $form->field($model, 'paisDescripcion', 
    ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
    ->widget(
        TypeAhead::className(), [
        'name' => 'codpais',
        'options' => ['placeholder' => 'Ingrese País de origen ...', 'class' => 'form-control', 'id' => 'paciente-codpais'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['pais/query']) . '&q=%QUERY',
                    'wildcard' => '%QUERY'
                ],
                'limit' => 10
            ]
        ],
        'pluginEvents' => [
            'typeahead:selected' => 'function(e,datum) { setPacientePais(e,datum); }',
            'typeahead:autocompleted' => 'function(e,datum) { setPacientePais(e,datum); }',
        ]
    ]); ?>

    <?= Html::activeHiddenInput($model, 'PA_CODPRO') ?>
    <?= $form->field($model, 'provinciaDescripcion', 
    ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
    ->widget(
        TypeAhead::className(), [
        'name' => 'codpro',
        'options' => ['placeholder' => 'Ingrese Provincia ...', 'class' => 'form-control', 'id' => 'paciente-codpro'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['provincia/query']) . '&q=%QUERY',
                    'wildcard' => '%QUERY'
                ],
                'limit' => 10
            ]
        ],
        'pluginEvents' => [
            'typeahead:selected' => 'function(e,datum) { setPacienteProvincia(e,datum); }',
            'typeahead:autocompleted' => 'function(e,datum) { setPacienteProvincia(e,datum); }',
        ]
    ]); ?>

    <?= Html::activeHiddenInput($model, 'PA_CODPAR') ?>
    <?= $form->field($model, 'partidoDescripcion', 
    ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
    ->widget(
        TypeAhead::className(), [
        'name' => 'codpar',
        'options' => ['placeholder' => 'Ingrese Partido ...', 'class' => 'form-control', 'id' => 'paciente-codpar'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['partido/query']) . '&q=%QUERY',
                    'wildcard' => '%QUERY'
                ],
                'limit' => 10
            ]
        ],
        'pluginEvents' => [
            'typeahead:selected' => 'function(e,datum) { setPacientePartido(e,datum); }',
            'typeahead:autocompleted' => 'function(e,datum) { setPacientePartido(e,datum); }',
        ]
    ]); ?>
    
    <?= Html::activeHiddenInput($model, 'PA_CODLOC') ?>
    <?= $form->field($model, 'localidadDescripcion', 
    ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
    ->widget(
        TypeAhead::className(), [
        'name' => 'codloc',
        'options' => ['placeholder' => 'Ingrese Localidad ...', 'class' => 'form-control', 'id' => 'paciente-codloc'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['localidad/query']) . '&q=%QUERY',
                    'wildcard' => '%QUERY'
                ],
                'limit' => 10
            ]
        ],
        'pluginEvents' => [
            'typeahead:selected' => 'function(e,datum) { setPacienteLocalidad(e,datum); }',
            'typeahead:autocompleted' => 'function(e,datum) { setPacienteLocalidad(e,datum); }',
        ]
    ]); ?>

    <?= $form->field($model, 'PA_DIREC', 
            ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
            ->textInput(['readonly' => 'true', 'maxlength' => true]) ?>

    <div class="row">
        <div class="col-md-4">
        <?= Html::activeHiddenInput($model, 'PA_CODCALL', ['onchange' => 'armarDomicilio();']) ?>
        <?= $form->field($model, 'calleDescripcion', 
        ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
        ->widget(
            TypeAhead::className(), [
            'name' => 'codcall',
            'options' => ['placeholder' => 'Ingrese Calle ...', 'class' => 'form-control', 'id' => 'paciente-codcall'],
            'pluginOptions' => ['highlight'=>true],
            'dataset' => [
                [
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                    'display' => 'value',
                    'remote' => [
                        'url' => Url::to(['calle/query']) . '&q=%QUERY',
                        'wildcard' => '%QUERY'
                    ],
                    'limit' => 10
                ]
            ],
            'pluginEvents' => [
                'typeahead:selected' => 'function(e,datum) { setPacienteCalle(e,datum); }',
                'typeahead:autocompleted' => 'function(e,datum) { setPacienteCalle(e,datum); }',
                'typeahead:change' => 'function () { buscarPorDomicilioFechaNacimiento(); }',
            ]
        ]); ?>
        </div>

        <div class="col-md-2">
        <?= $form->field($model, 'PA_NROCALL', 
                ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
                ->textInput(['maxlength' => true, 'onchange' => 'armarDomicilio(); buscarPorDomicilioFechaNacimiento();']) ?>
        </div> 

        <div class="col-md-2">
        <?= $form->field($model, 'PA_CUERPO', 
                ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
                ->textInput(['maxlength' => true, 'onchange' => 'armarDomicilio();']) ?>
        </div>

        <div class="col-md-2">
        <?= $form->field($model, 'PA_PISO', 
                ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
                ->textInput(['maxlength' => true, 'onchange' => 'armarDomicilio();']) ?>
        </div>

        <div class="col-md-2">
        <?= $form->field($model, 'PA_DPTO', 
                ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
                ->textInput(['maxlength' => true, 'onchange' => 'armarDomicilio();']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= Html::activeHiddenInput($model, 'PA_BARRIO') ?>
            <?= $form->field($model, 'barrioDescripcion', 
            ['horizontalCssClasses' => ['label' => 'col-md-4', 'offset' => 'col-md-offset-2', 'wrapper' => 'col-md-6']])
            ->widget(
                TypeAhead::className(), [
                'name' => 'barrio',
                'options' => ['placeholder' => 'Ingrese Barrio ...', 'class' => 'form-control', 'id' => 'paciente-barrio'],
                'pluginOptions' => ['highlight'=>true],
                'dataset' => [
                    [
                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                        'display' => 'value',
                        'remote' => [
                            'url' => Url::to(['barrio/query']) . '&q=%QUERY',
                            'wildcard' => '%QUERY'
                        ],
                        'limit' => 10
                    ]
                ],
                'pluginEvents' => [
                    'typeahead:selected' => 'function(e,datum) { setPacienteBarrio(e,datum); }',
                    'typeahead:autocompleted' => 'function(e,datum) { setPacienteBarrio(e,datum); }',
                ]
            ]); ?>
        </div>

        <div class="col-md-6">
            <?= Html::activeHiddenInput($model, 'PA_TIPOVIV') ?>
            <?= $form->field($model, 'tipoViviendaDescripcion', 
            ['horizontalCssClasses' => ['label' => 'col-md-4', 'offset' => 'col-md-offset-2', 'wrapper' => 'col-md-6']])
            ->widget(
                TypeAhead::className(), [
                'name' => 'tipoviv',
                'options' => ['placeholder' => 'Ingrese Tipo de Vivienda ...', 'class' => 'form-control', 'id' => 'paciente-tipoviv'],
                'pluginOptions' => ['highlight'=>true],
                'dataset' => [
                    [
                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                        'display' => 'value',
                        'remote' => [
                            'url' => Url::to(['tipo-vivienda/query']) . '&q=%QUERY',
                            'wildcard' => '%QUERY'
                        ],
                        'limit' => 10
                    ]
                ],
                'pluginEvents' => [
                    'typeahead:selected' => 'function(e,datum) { setPacienteTipoVivienda(e,datum); }',
                    'typeahead:autocompleted' => 'function(e,datum) { setPacienteTipoVivienda(e,datum); }',
                ]
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'PA_TELEF', 
                ['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])
                ->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
        <?= $form->field($model, 'PA_EMAIL', 
                ['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])
                ->widget(MaskedInput::className(), ['clientOptions' => ['alias' =>  'email']]) ?>
        </div>
    </div>

    <div id="sugerencias-domicilio-fnac"></div>

    <hr />

    <div class="row">
        <div class="col-md-4">
            <?= Html::activeHiddenInput($model, 'PA_CODOS') ?>
            <?= $form->field($model, 'obraSocialDescripcion', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->widget(
                TypeAhead::className(), [
                'name' => 'codos',
                'options' => ['placeholder' => 'Ingrese Obra Social ...', 'class' => 'form-control', 'id' => 'paciente-codos'],
                'pluginOptions' => ['highlight'=>true],
                'dataset' => [
                    [
                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                        'display' => 'value',
                        'remote' => [
                            'url' => Url::to(['obra-social/query']) . '&q=%QUERY',
                            'wildcard' => '%QUERY'
                        ],
                        'limit' => 10
                    ]
                ],
                'pluginEvents' => [
                    'typeahead:selected' => 'function(e,datum) { setPacienteObraSocial(e,datum); }',
                    'typeahead:autocompleted' => 'function(e,datum) { setPacienteObraSocial(e,datum); }',
                ]
            ]); ?>
        </div>

        <div class="col-md-4">
        <?= $form->field($model, 'PA_NROAFI', 
                ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
                ->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-4">
        <?= $form->field($model, 'PA_ASOCIAD', 
                ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
                ->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'PA_NIVEL', 
                ['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])
                ->textInput(['readonly' => true, 'maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
        <?= $form->field($model, 'PA_VENNIV', 
                ['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])
                ->textInput(['readonly' => true]) ?>
        </div>
    </div>

    <hr />
    
    <?= Html::activeHiddenInput($model, 'PA_LOCNAC') ?>
    <?= $form->field($model, 'localidadNacimientoDescripcion', 
    ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
    ->widget(
        TypeAhead::className(), [
        'name' => 'locnac',
        'options' => ['placeholder' => 'Ingrese Localidad ...', 'class' => 'form-control', 'id' => 'paciente-locnac'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['localidad/query']) . '&q=%QUERY',
                    'wildcard' => '%QUERY'
                ],
                'limit' => 10
            ]
        ],
        'pluginEvents' => [
            'typeahead:selected' => 'function(e,datum) { setPacienteLocalidadNacimiento(e,datum); }',
            'typeahead:autocompleted' => 'function(e,datum) { setPacienteLocalidadNacimiento(e,datum); }',
        ]
    ]); ?>

    <?= Html::activeHiddenInput($model, 'PA_PROVNAC') ?>
    <?= $form->field($model, 'provinciaNacimientoDescripcion', 
    ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
    ->widget(
        TypeAhead::className(), [
        'name' => 'provnac',
        'options' => ['placeholder' => 'Ingrese Provincia ...', 'class' => 'form-control', 'id' => 'paciente-provnac'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['provincia/query']) . '&q=%QUERY',
                    'wildcard' => '%QUERY'
                ],
                'limit' => 10
            ]
        ],
        'pluginEvents' => [
            'typeahead:selected' => 'function(e,datum) { setPacienteProvinciaNacimiento(e,datum); }',
            'typeahead:autocompleted' => 'function(e,datum) { setPacienteProvinciaNacimiento(e,datum); }',
        ]
    ]); ?>

    <?= Html::activeHiddenInput($model, 'PA_PARTIDONAC') ?>
    <?= $form->field($model, 'partidoNacimientoDescripcion', 
    ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
    ->widget(
        TypeAhead::className(), [
        'name' => 'partidonac',
        'options' => ['placeholder' => 'Ingrese Partido ...', 'class' => 'form-control', 'id' => 'paciente-partidonac'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['partido/query']) . '&q=%QUERY',
                    'wildcard' => '%QUERY'
                ],
                'limit' => 10
            ]
        ],
        'pluginEvents' => [
            'typeahead:selected' => 'function(e,datum) { setPacientePartidoNacimiento(e,datum); }',
            'typeahead:autocompleted' => 'function(e,datum) { setPacientePartidoNacimiento(e,datum); }',
        ]
    ]); ?>

    <?= $form->field($model, 'PA_APEMA', 
            ['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-9']])
            ->textInput(['maxlength' => true]) ?>

    <hr />

    <div class="row">
        <div class="col-md-6">
            <?= Html::activeHiddenInput($model, 'PA_NIVINST') ?>
            <?= $form->field($model, 'nivelInstruccionDescripcion', 
            ['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])
            ->widget(
                TypeAhead::className(), [
                'name' => 'nivinst',
                'options' => ['placeholder' => 'Ingrese Nivel de Instrucción ...', 'class' => 'form-control', 'id' => 'paciente-nivinst'],
                'pluginOptions' => ['highlight'=>true],
                'dataset' => [
                    [
                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                        'display' => 'value',
                        'remote' => [
                            'url' => Url::to(['nivel-instruccion/query']) . '&q=%QUERY',
                            'wildcard' => '%QUERY'
                        ],
                        'limit' => 10
                    ]
                ],
                'pluginEvents' => [
                    'typeahead:selected' => 'function(e,datum) { setPacienteNivelInstruccion(e,datum); }',
                    'typeahead:autocompleted' => 'function(e,datum) { setPacienteNivelInstruccion(e,datum); }',
                ]
            ]); ?>
        </div>

        <div class="col-md-6">
            <?= Html::activeHiddenInput($model, 'PA_SITLABO') ?>
            <?= $form->field($model, 'situacionLaboralDescripcion', 
            ['horizontalCssClasses' => ['label' => 'col-md-4', 'wrapper' => 'col-md-8']])
            ->widget(
                TypeAhead::className(), [
                'name' => 'sitlabo',
                'options' => ['placeholder' => 'Ingrese Situación Laboral ...', 'class' => 'form-control', 'id' => 'paciente-sitlabo'],
                'pluginOptions' => ['highlight'=>true],
                'dataset' => [
                    [
                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                        'display' => 'value',
                        'remote' => [
                            'url' => Url::to(['situacion-laboral/query']) . '&q=%QUERY',
                            'wildcard' => '%QUERY'
                        ],
                        'limit' => 10
                    ]
                ],
                'pluginEvents' => [
                    'typeahead:selected' => 'function(e,datum) { setPacienteSituacionLaboral(e,datum); }',
                    'typeahead:autocompleted' => 'function(e,datum) { setPacienteSituacionLaboral(e,datum); }',
                ]
            ]); ?>
        </div>
    </div>

    <?= Html::activeHiddenInput($model, 'PA_OCUPAC') ?>
    <?= $form->field($model, 'ocupacionDescripcion', 
    ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
    ->widget(
        TypeAhead::className(), [
        'name' => 'ocupac',
        'options' => ['placeholder' => 'Ingrese Ocupación ...', 'class' => 'form-control', 'id' => 'paciente-ocupac'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['ocupacion/query']) . '&q=%QUERY',
                    'wildcard' => '%QUERY'
                ],
                'limit' => 10
            ]
        ],
        'pluginEvents' => [
            'typeahead:selected' => 'function(e,datum) { setPacienteOcupacion(e,datum); }',
            'typeahead:autocompleted' => 'function(e,datum) { setPacienteOcupacion(e,datum); }',
        ]
    ]); ?>

    <?= $form->field($model, 'PA_EMPEMPL', 
            ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
            ->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PA_EMPDIR', 
            ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
            ->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PA_CUITEMP', 
            ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
            ->widget(MaskedInput::className(), ['mask' => '99-99999999-9']) ?>

    <?= Html::activeHiddenInput($model, 'PA_ART') ?>
    <?= $form->field($model, 'artDescripcion', 
    ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
    ->widget(
        TypeAhead::className(), [
        'name' => 'art',
        'options' => ['placeholder' => 'Ingrese ART ...', 'class' => 'form-control', 'id' => 'paciente-art'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['obra-social/query']) . '&q=%QUERY&art=t',
                    'wildcard' => '%QUERY'
                ],
                'limit' => 10
            ]
        ],
        'pluginEvents' => [
            'typeahead:selected' => 'function(e,datum) { setPacienteOcupacion(e,datum); }',
            'typeahead:autocompleted' => 'function(e,datum) { setPacienteOcupacion(e,datum); }',
        ]
    ]); ?>

    <hr />
    
    <?= $form->field($model, 'PA_APEFA', 
            ['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-9']])
            ->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PA_TELFA', 
            ['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-9']])
            ->textInput(['maxlength' => true]) ?>

    <hr />

    <?= $form->field($model, 'PA_OBSERV', 
            ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])
            ->textarea(['rows' => 6]) ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Volver', ['ambulatorios_ventanilla/create'], ['class'=>'btn btn-danger']);?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

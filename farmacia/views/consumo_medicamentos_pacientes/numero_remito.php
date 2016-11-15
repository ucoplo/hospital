<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\dialog\Dialog;
use farmacia\assets\Vale_FarmaciaAsset;
use \kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Numero_remito */
/* @var $form yii\widgets\ActiveForm */

echo Dialog::widget();

Vale_FarmaciaAsset::register($this);

if ($model->VR_CONDPAC=='A')
    $tipo = "Ambulatorios";
else
    $tipo = "Internados";


$this->title = 'Número Remito';
$this->params['breadcrumbs'][] = ['label' => 'Consumo Medicamentos Pacientes '.$tipo, 'url' => ['index','condpac'=>$model->VR_CONDPAC]];
$this->params['breadcrumbs'][] = ['label' => 'Vales de Servicios Disponibles - '.$tipo, 'url' => ['seleccion_servicio','condpac'=>$model->VR_CONDPAC]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="numero-remito-create">

    <h2><?= Html::encode('Remito Actual - '.$tipo) ?></h2>
    <div class="numero-remito-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
           <div class="col-md-3">
              <?= $form->field($model, 'VR_NROREM')->textInput(['readonly'=>true]) ?>
            </div>
            <?php if  ($model->procesado){?>
                <div class="col-md-3 alert alert-danger" id="remito_procesado">
                   El Remito ya fue procesado 
                </div>
            <?php } ?>
        </div> 
        <?= $form->field($model, 'VR_SERSOL')->hiddenInput()->label(false);?>


        <?= Html::label('Servicio Solicitante', 'servicio') ?>
        <?php $servicio = ((isset($model->VR_SERSOL))?$model->servicio->SE_DESCRI:'');?>
        <?= Html::input('text', 'servicio',$servicio, ['class'=>'form-control','readonly'=>true]) ?> 
         <div class="help-block"></div>


        <div class="row">
           <div class="col-md-6">
             <span class="hide oculto"><?= $form->field($model, 'VR_FECDES')->widget(DateControl::classname(), [
                    'type'=>DateControl::FORMAT_DATE,
                    
                    'ajaxConversion'=>false,
                    'options' => [
                        'removeButton' => false,

                        'options' => ['placeholder' => 'Seleccione una fecha ...', ],
                        'pluginOptions' => [
                            'autoclose' => true,
                        ]
                    ]
                ]);?>
            </span>
            <span class="visible">
                <?= Html::label('Fecha Desde', 'fechadesde') ?>
                <?php $fecha_desde = (isset($model->VR_FECDES))?Yii::$app->formatter->asDate($model->VR_FECDES,'php:d-m-Y'):'';?>
                <?= Html::input('text', 'fechadesde',$fecha_desde, ['class'=>'form-control','readonly'=>true]) ?> 
            </span> 
           </div>
           <div class="col-md-6">
            <span class="hide oculto"><?= $form->field($model, 'VR_HORDES', ['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-5']])
                ->textInput(['maxlength' => true])
                ->widget(TimePicker::classname(), [
                    'pluginOptions' => [
                        'showMeridian' => false,
                        'defaultTime' => false,
                        'minuteStep' => 5,
                    ],
                ]);?></span>
            <span class="visible">
                <?= Html::label('Hora Desde', 'horadesde') ?>
                <?= Html::input('text', 'horadesde',$model->VR_HORDES, ['class'=>'form-control','readonly'=>true]) ?> 
            </span> 
           
           </div>
        </div> 

        <div class="row">
           <div class="col-md-6">
              <span class="hide oculto"> <?= $form->field($model, 'VR_FECHAS')->widget(DateControl::classname(), [
                    'type'=>DateControl::FORMAT_DATE,
                   
                    'ajaxConversion'=>false,
                    'options' => [
                        'removeButton' => false,
                        'options' => ['placeholder' => 'Seleccione una fecha ...', ],
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ]);?>
             </span>
            <span class="visible">
                <?= Html::label('Fecha Hasta', 'fechahasta') ?>
                <?php $fecha_hasta = (isset($model->VR_FECHAS))?Yii::$app->formatter->asDate($model->VR_FECHAS,'php:d-m-Y'):'';?>
                <?= Html::input('text', 'fechahasta',$fecha_hasta, ['class'=>'form-control','readonly'=>true]) ?> 
            </span> 
           </div>
           <div class="col-md-6">
            <span class="hide oculto"><?= $form->field($model, 'VR_HORHAS', ['horizontalCssClasses' => ['label' => 'col-md-3', 'wrapper' => 'col-md-5']])
                ->textInput(['maxlength' => true])
                ->widget(TimePicker::classname(), [
                    
                    
                    'pluginOptions' => [
                        'showMeridian' => false,
                        'defaultTime' => false,
                        'minuteStep' => 5,
                      
                    ],
                ]);?>
             </span>
             <span class="visible">
                <?= Html::label('Hora Hasta', 'horahasta') ?>
                <?= Html::input('text', 'horahasta',$model->VR_HORHAS, ['class'=>'form-control','readonly'=>true]) ?> 
            </span> 
           </div>
        </div> 


        <?= $form->field($model, 'VR_CONDPAC')->hiddenInput()->label(false);?>
        <?= Html::hiddenInput('es_remito_nuevo', '0',['id'=>'es_remito_nuevo']);?>

        <div class="form-group">

            <?= Html::submitButton('Continuar Remito' , ['id'=>'guardar_remito','class' => $model->VR_NROREM ? 'btn btn-success' : 'btn btn-success hide','disabled'=>$model->procesado ? true : false]) ?>

             <?= Html::Button('Nuevo Remito' , ['class' => 'btn btn-success','id'=>'nuevo_remito']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
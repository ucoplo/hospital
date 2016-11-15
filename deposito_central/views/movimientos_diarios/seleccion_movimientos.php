<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Movimientos_diarios */

$this->title = 'Movimientos Diarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimientos-diarios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'DM_FECHA')->widget(DateControl::classname(), [
                'type'=>DateControl::FORMAT_DATE,
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
                <?= $form->field($model, 'DM_DEPOSITO')->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito']);?>
            </div>
     </div>

   

    <div class="form-group">
        <?= Html::submitButton('Seleccionar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
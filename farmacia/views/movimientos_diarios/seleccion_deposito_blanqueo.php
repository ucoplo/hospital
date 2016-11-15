<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Movimientos_diarios */

$this->title = 'Blanqueo Stock Lotes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimientos-diarios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'MD_FECHA')->hiddenInput()->label(false);?>

    <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'MD_DEPOSITO')->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito']);?>
            </div>
     </div>

   

    <div class="form-group">
        <?= Html::submitButton('Seleccionar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
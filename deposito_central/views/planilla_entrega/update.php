<?php

use yii\helpers\Html;
use deposito_central\assets\Planilla_entregaAsset;
use kartik\dialog\Dialog;

echo Dialog::widget();

Planilla_entregaAsset::register($this);


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Planilla_entrega */

$this->title = 'Update Consumo Medicamentos Granel: ' . $model->PE_NROREM;
$this->params['breadcrumbs'][] = ['label' => 'Consumo Medicamentos Granels', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->PE_NROREM, 'url' => ['view', 'id' => $model->PE_NROREM]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="consumo-medicamentos-granel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use farmacia\assets\Planilla_GranelAsset;
use kartik\dialog\Dialog;

echo Dialog::widget();

Planilla_GranelAsset::register($this);


/* @var $this yii\web\View */
/* @var $model farmacia\models\Consumo_medicamentos_granel */

$this->title = 'Update Consumo Medicamentos Granel: ' . $model->CM_NROREM;
$this->params['breadcrumbs'][] = ['label' => 'Consumo Medicamentos Granels', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->CM_NROREM, 'url' => ['view', 'id' => $model->CM_NROREM]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="consumo-medicamentos-granel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

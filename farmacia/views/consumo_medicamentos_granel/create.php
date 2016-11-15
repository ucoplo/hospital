<?php

use yii\helpers\Html;
use farmacia\assets\Planilla_GranelAsset;
use kartik\dialog\Dialog;

echo Dialog::widget();

Planilla_GranelAsset::register($this);


/* @var $this yii\web\View */
/* @var $model farmacia\models\Consumo_medicamentos_granel */

$this->title = 'Nuevo Remito a Granel';
$this->params['breadcrumbs'][] = ['label' => 'Planillas de Retiro a Granel', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumo-medicamentos-granel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

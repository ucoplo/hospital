<?php

use yii\helpers\Html;
use deposito_central\assets\Planilla_entregaAsset;
use kartik\dialog\Dialog;

echo Dialog::widget();

Planilla_entregaAsset::register($this);


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Planilla_entrega */

$this->title = 'Nueva Planilla de entrega';
$this->params['breadcrumbs'][] = ['label' => 'Planillas de entrega', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumo-medicamentos-granel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

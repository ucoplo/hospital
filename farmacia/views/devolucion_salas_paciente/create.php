<?php

use yii\helpers\Html;
use farmacia\assets\Devolucion_PacienteAsset;
use kartik\dialog\Dialog;

echo Dialog::widget();

Devolucion_PacienteAsset::register($this);


/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_salas_paciente */

$this->title = 'Nueva Devolución de Sala Paciente';
$this->params['breadcrumbs'][] = ['label' => 'Devoluciones de Salas Paciente', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-salas-paciente-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

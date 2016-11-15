<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_salas_paciente */

$this->title = 'Update Devolucion Salas Paciente: ' . $model->DE_NRODEVOL;
$this->params['breadcrumbs'][] = ['label' => 'Devolucion Salas Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->DE_NRODEVOL, 'url' => ['view', 'id' => $model->DE_NRODEVOL]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="devolucion-salas-paciente-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Paciente */

$this->title = 'Modificar Paciente: ' . '[' .$model->PA_TIPDOC . ' ' . $model->PA_NUMDOC . '] ' . $model->PA_APENOM;
$this->params['breadcrumbs'][] = ['label' => 'Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->PA_TIPDOC, 'url' => ['view', 'PA_TIPDOC' => $model->PA_TIPDOC, 'PA_NUMDOC' => $model->PA_NUMDOC, 'PA_HISCLI' => $model->PA_HISCLI]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="paciente-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

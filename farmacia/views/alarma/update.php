<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Alarma */

$this->title = 'Modificar Alarma: ' . $model->AL_CODMON;
$this->params['breadcrumbs'][] = ['label' => 'Alarmas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->AL_CODMON, 'url' => ['view', 'AL_CODMON' => $model->AL_CODMON, 'AL_DEPOSITO' => $model->AL_DEPOSITO]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="alarma-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

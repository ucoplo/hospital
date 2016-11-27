<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Pedido_reposicion_farmacia */

$this->title = 'Update Pedido Reposicion Farmacia: ' . $model->PE_NROPED;
$this->params['breadcrumbs'][] = ['label' => 'Pedido Reposicion Farmacias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->PE_NROPED, 'url' => ['view', 'id' => $model->PE_NROPED]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pedido-reposicion-farmacia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

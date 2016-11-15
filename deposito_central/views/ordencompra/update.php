<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\OrdenCompra */

$this->title = 'Update Orden Compra: ' . $model->OC_NRO;
$this->params['breadcrumbs'][] = ['label' => 'Orden Compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->OC_NRO, 'url' => ['view', 'id' => $model->OC_NRO]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orden-compra-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

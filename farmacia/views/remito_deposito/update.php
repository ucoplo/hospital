<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_deposito */

$this->title = 'Update Remito Deposito: ' . $model->RS_NROREM;
$this->params['breadcrumbs'][] = ['label' => 'Remito Depositos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->RS_NROREM, 'url' => ['view', 'id' => $model->RS_NROREM]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="remito-deposito-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

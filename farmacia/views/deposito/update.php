<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Deposito */

$this->title = 'Modificar Depósito: ' . $model->DE_CODIGO.' - '.$model->DE_DESCR;
$this->params['breadcrumbs'][] = ['label' => 'Depositos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->DE_CODIGO, 'url' => ['view', 'id' => $model->DE_CODIGO]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="deposito-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

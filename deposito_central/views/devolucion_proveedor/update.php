<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Devolucion_proveedor */

$this->title = 'Modificación Devolución Proveedor Nro.: ' . $model->DD_NROREM;
$this->params['breadcrumbs'][] = ['label' => 'Devoluciones a Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->DD_NROREM, 'url' => ['view', 'id' => $model->DD_NROREM]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="devolucion-proveedor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

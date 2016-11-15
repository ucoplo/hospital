<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Proveedores */

$this->title = 'Modificar Proveedor: ' . $model->PR_CODIGO;
$this->params['breadcrumbs'][] = ['label' => 'Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->PR_CODIGO, 'url' => ['view', 'id' => $model->PR_CODIGO]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="proveedores-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

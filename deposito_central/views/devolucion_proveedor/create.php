<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Devolucion_proveedor */

$this->title = 'Nueva Devolución';
$this->params['breadcrumbs'][] = ['label' => 'Devoluciones a Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-proveedor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

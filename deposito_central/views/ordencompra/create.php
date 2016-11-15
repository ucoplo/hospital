<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model deposito_central\models\OrdenCompra */

$this->title = 'Create Orden Compra';
$this->params['breadcrumbs'][] = ['label' => 'Orden Compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orden-compra-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

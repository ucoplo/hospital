<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\OrdenCompra */

$this->title = $model->OC_NRO;
$this->params['breadcrumbs'][] = ['label' => 'Orden Compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orden-compra-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->OC_NRO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->OC_NRO], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'OC_NRO',
            'OC_PROVEED',
            'OC_FECHA',
            'OC_FINALIZADA',
            'OC_PEDADQ',
        ],
    ]) ?>

</div>

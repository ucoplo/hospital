<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Pedido_reposicion_farmacia */

$this->title = $model->PE_NROPED;
$this->params['breadcrumbs'][] = ['label' => 'Pedido Reposicion Farmacias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedido-reposicion-farmacia-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->PE_NROPED], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->PE_NROPED], [
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
            'PE_NROPED',
            'PE_FECHA',
            'PE_HORA',
            'PE_SERSOL',
            'PE_DEPOSITO',
            'PE_REFERENCIA:ntext',
            'PE_CLASE',
            'PE_SUPERV',
            'PE_PROCESADO',
        ],
    ]) ?>

</div>

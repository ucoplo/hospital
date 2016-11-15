<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_deposito */

$this->title = $model->RS_NROREM;
$this->params['breadcrumbs'][] = ['label' => 'Remito Depositos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remito-deposito-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->RS_NROREM], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->RS_NROREM], [
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
            'RS_CODEP',
            'RS_NROREM',
            'RS_FECHA',
            'RS_HORA',
            'RS_CODOPE',
            'RS_NUMPED',
            'RS_SERSOL',
            'RS_IMPORT',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Movimientos_diarios */

$this->title = $model->DM_FECHA;
$this->params['breadcrumbs'][] = ['label' => 'Movimientos Diarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimientos-diarios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'DM_FECHA' => $model->DM_FECHA, 'DM_CODMOV' => $model->DM_CODMOV, 'DM_FECVTO' => $model->DM_FECVTO, 'DM_CODART' => $model->DM_CODART, 'DM_DEPOSITO' => $model->DM_DEPOSITO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'DM_FECHA' => $model->DM_FECHA, 'DM_CODMOV' => $model->DM_CODMOV, 'DM_FECVTO' => $model->DM_FECVTO, 'DM_CODART' => $model->DM_CODART, 'DM_DEPOSITO' => $model->DM_DEPOSITO], [
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
            'DM_FECHA',
            'DM_CODMOV',
            'DM_CANT',
            'DM_FECVTO',
            'DM_CODART',
            'DM_DEPOSITO',
        ],
    ]) ?>

</div>

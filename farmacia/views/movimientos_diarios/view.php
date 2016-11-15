<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Movimientos_diarios */

$this->title = $model->MD_FECHA;
$this->params['breadcrumbs'][] = ['label' => 'Movimientos Diarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimientos-diarios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'MD_FECHA' => $model->MD_FECHA, 'MD_CODMOV' => $model->MD_CODMOV, 'MD_FECVEN' => $model->MD_FECVEN, 'MD_CODMON' => $model->MD_CODMON, 'MD_DEPOSITO' => $model->MD_DEPOSITO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'MD_FECHA' => $model->MD_FECHA, 'MD_CODMOV' => $model->MD_CODMOV, 'MD_FECVEN' => $model->MD_FECVEN, 'MD_CODMON' => $model->MD_CODMON, 'MD_DEPOSITO' => $model->MD_DEPOSITO], [
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
            'MD_FECHA',
            'MD_CODMOV',
            'MD_CANT',
            'MD_FECVEN',
            'MD_CODMON',
            'MD_DEPOSITO',
        ],
    ]) ?>

</div>

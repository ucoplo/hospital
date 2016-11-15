<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Motivo_perdida */

$this->title = $model->MP_COD;
$this->params['breadcrumbs'][] = ['label' => 'Motivo de Pérdidas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motivo-perdida-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->MP_COD], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->MP_COD], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro de eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'MP_COD',
            'MP_NOM',
        ],
    ]) ?>

</div>

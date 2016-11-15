<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Via */

$this->title = $model->VI_CODIGO;
$this->params['breadcrumbs'][] = ['label' => 'Vías', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="via-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->VI_CODIGO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->VI_CODIGO], [
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
            'VI_CODIGO',
            'VI_DESCRI',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Techo_articulo */

$this->title = $model->TA_CODSERV;
$this->params['breadcrumbs'][] = ['label' => 'Techos Artículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="techo-articulo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'TA_CODSERV' => $model->TA_CODSERV, 'TA_DEPOSITO' => $model->TA_DEPOSITO, 'TA_CODART' => $model->TA_CODART], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'TA_CODSERV' => $model->TA_CODSERV, 'TA_DEPOSITO' => $model->TA_DEPOSITO, 'TA_CODART' => $model->TA_CODART], [
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
            'TA_CODSERV',
            'TA_DEPOSITO',
            'TA_CODART',
            'TA_CANTID',
        ],
    ]) ?>

</div>

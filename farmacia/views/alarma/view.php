<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Alarma */

$this->title = $model->AL_CODMON;
$this->params['breadcrumbs'][] = ['label' => 'Alarmas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alarma-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'AL_CODMON' => $model->AL_CODMON, 'AL_DEPOSITO' => $model->AL_DEPOSITO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'AL_CODMON' => $model->AL_CODMON, 'AL_DEPOSITO' => $model->AL_DEPOSITO], [
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
            
            [
                'attribute'=>'monodroga.AG_NOMBRE',
                'label'=>"Monodroga",
            ],
            [
                'attribute'=>'deposito.DE_DESCR',
                'label'=>"Depósito",
            ],
            
            'AL_MIN',
            'AL_MAX',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Deposito */

$this->title = $model->DE_CODIGO;
$this->params['breadcrumbs'][] = ['label' => 'Depósitos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposito-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->DE_CODIGO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->DE_CODIGO], [
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
            'DE_CODIGO',
            'DE_DESCR',
        ],
    ]) ?>

</div>

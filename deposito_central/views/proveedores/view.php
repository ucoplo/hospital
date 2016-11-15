<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Proveedores */

$this->title = $model->PR_CODIGO.' - '.$model->PR_RAZONSOC;
$this->params['breadcrumbs'][] = ['label' => 'Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proveedores-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->PR_CODIGO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->PR_CODIGO], [
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
            'PR_CODIGO',
            'PR_RAZONSOC',
            'PR_CONTACTO',
            'PR_TELEF',
            'PR_EMAIL:email',
            'PR_CODRAFAM',
            'PR_OBS:ntext',
        ],
    ]) ?>

</div>

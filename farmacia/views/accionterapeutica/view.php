<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Accionterapeutica */

$this->title = $model->AC_COD;
$this->params['breadcrumbs'][] = ['label' => 'Accion Terapeutica', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accionterapeutica-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->AC_COD], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->AC_COD], [
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
            'AC_COD',
            'AC_DESCRI',
        ],
    ]) ?>

</div>

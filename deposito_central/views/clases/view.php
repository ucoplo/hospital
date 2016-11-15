<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Clases */

$this->title = $model->CL_COD."-".$model->CL_NOM;
$this->params['breadcrumbs'][] = ['label' => 'Clases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clases-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->CL_COD], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->CL_COD], [
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
            'CL_COD',
            'CL_NOM',
        ],
    ]) ?>

</div>

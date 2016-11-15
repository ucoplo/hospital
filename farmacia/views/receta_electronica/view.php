<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Receta_electronica */

$this->title = $model->RE_NRORECETA;
$this->params['breadcrumbs'][] = ['label' => 'Receta Electronicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receta-electronica-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->RE_NRORECETA], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->RE_NRORECETA], [
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
            'RE_NRORECETA',
            'RE_HISCLI',
            'RE_FECINI',
            'RE_FECFIN',
            'RE_MEDICO',
            'RE_NOTA:ntext',
        ],
    ]) ?>

</div>

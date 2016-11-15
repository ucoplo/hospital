<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Productos_kairos */

$this->title = 'Update Productos Kairos: ' . $model->codigo;
$this->params['breadcrumbs'][] = ['label' => 'Productos Kairos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigo, 'url' => ['view', 'id' => $model->codigo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="productos-kairos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

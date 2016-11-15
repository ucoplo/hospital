<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Droga */

$this->title = 'Modificar Droga: ' . $model->DR_CODIGO.' - '.$model->DR_DESCRI;
$this->params['breadcrumbs'][] = ['label' => 'Drogas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->DR_CODIGO, 'url' => ['view', 'id' => $model->DR_CODIGO]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="droga-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

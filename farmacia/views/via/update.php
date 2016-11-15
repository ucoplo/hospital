<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Via */

$this->title = 'Modificar Vía: ' . $model->VI_CODIGO.' - '.$model->VI_DESCRI;
$this->params['breadcrumbs'][] = ['label' => 'Vías', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->VI_CODIGO, 'url' => ['view', 'id' => $model->VI_CODIGO]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="via-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

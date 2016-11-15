<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Perdidas */

$this->title = 'Update Perdidas: ' . $model->PE_NROREM;
$this->params['breadcrumbs'][] = ['label' => 'Perdidas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->PE_NROREM, 'url' => ['view', 'id' => $model->PE_NROREM]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="perdidas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

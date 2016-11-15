<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Pedentre */

$this->title = 'Update Pedentre: ' . $model->PE_NROPED;
$this->params['breadcrumbs'][] = ['label' => 'Pedentres', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->PE_NROPED, 'url' => ['view', 'id' => $model->PE_NROPED]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pedentre-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

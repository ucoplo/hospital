<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_Adquisicion */

$this->title = 'Update Remito  Adquisicion: ' . $model->AR_NUM;
$this->params['breadcrumbs'][] = ['label' => 'Remito  Adquisicions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->AR_NUM, 'url' => ['view', 'id' => $model->AR_NUM]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="remito--adquisicion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Medic */

$this->title = 'Modificar Medicamento: ' . $model->ME_CODIGO.' - '.$model->ME_NOMCOM;
$this->params['breadcrumbs'][] = ['label' => 'Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ME_CODIGO, 'url' => ['view', 'id' => $model->ME_CODIGO]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="medic-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

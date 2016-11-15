<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Labo */

$this->title = 'Modificar Laboratorio: ' . ' ' . $model->LA_CODIGO." - ".$model->LA_NOMBRE;
$this->params['breadcrumbs'][] = ['label' => 'Laboratorios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->LA_CODIGO, 'url' => ['view', 'id' => $model->LA_CODIGO]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="labo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

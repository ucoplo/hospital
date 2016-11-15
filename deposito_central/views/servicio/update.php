<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Servicio */

$this->title = 'Modificar Servicio: ' . $model->SE_CODIGO.' - '.$model->SE_DESCRI;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->SE_CODIGO, 'url' => ['view', 'id' => $model->SE_CODIGO]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="servicio-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

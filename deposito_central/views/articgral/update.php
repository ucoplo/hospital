<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\ArticGral */

$this->title = 'Modificar Artículo: ' . $model->AG_CODIGO.' - '.$model->AG_NOMBRE;
$this->params['breadcrumbs'][] = ['label' => 'Artículos Generales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->AG_CODIGO, 'url' => ['view', 'AG_CODIGO' => $model->AG_CODIGO,'AG_DEPOSITO' => $model->AG_DEPOSITO]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="artic-gral-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

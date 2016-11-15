<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\ArticGral */

$this->title = 'Modificar Artículo: ' . $model->AG_CODIGO.' - '.$model->AG_NOMBRE;
$this->params['breadcrumbs'][] = ['label' => 'Artículos Generales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->AG_CODIGO, 'url' => ['view', 'id' => $model->AG_CODIGO]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="artic-gral-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

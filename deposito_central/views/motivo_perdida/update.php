<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Motivo_perdida */

$this->title = 'Modificar Motivo de Pérdida: ' . $model->MP_COD.' - '.$model->MP_NOM;
$this->params['breadcrumbs'][] = ['label' => 'Motivo Pérdidas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->MP_COD, 'url' => ['view', 'id' => $model->MP_COD]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="motivo-perdida-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

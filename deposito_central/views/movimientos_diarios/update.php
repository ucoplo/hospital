<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Movimientos_diarios */

$this->title = 'Movimientos Diarios: ' .Yii::$app->formatter->asDate($model->DM_FECHA,'php:d-m-Y');
$this->params['breadcrumbs'][] = ['label' => 'Movimientos Diarios', 'url' => ['seleccionar_movimientos']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="movimientos-diarios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

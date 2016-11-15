<?php

use yii\helpers\Html;
use farmacia\assets\Devolucion_SobranteAsset;
use kartik\dialog\Dialog;

echo Dialog::widget();

Devolucion_SobranteAsset::register($this);


/* @var $this yii\web\View */
/* @var $model farmacia\models\Devolucion_salas_granel */

$this->title = 'Nueva Devolución de Sala Sobrantes';
$this->params['breadcrumbs'][] = ['label' => 'Devoluciones de Salas Sobrantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-salas-granel-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

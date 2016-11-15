<?php

use yii\helpers\Html;
use deposito_central\assets\Devolucion_SobranteAsset;
use kartik\dialog\Dialog;

echo Dialog::widget();

Devolucion_SobranteAsset::register($this);


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Devolucion_salas */

$this->title = 'Nueva Devolución de Sala Sobrantes';
$this->params['breadcrumbs'][] = ['label' => 'Devoluciones de Salas Sobrantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-salas-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

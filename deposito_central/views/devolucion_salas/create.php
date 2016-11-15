<?php

use yii\helpers\Html;
use deposito_central\assets\Devolucion_SalasAsset;

Devolucion_SalasAsset::register($this);


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Devolucion_salas */

$this->title = 'Nueva Devolución de Sala';
$this->params['breadcrumbs'][] = ['label' => 'Devoluciones de Salas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-salas-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

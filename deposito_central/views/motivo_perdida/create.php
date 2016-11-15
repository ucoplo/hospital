<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Motivo_perdida */

$this->title = 'Crear Motivo de Pérdidas';
$this->params['breadcrumbs'][] = ['label' => 'Motivo de Pérdidas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motivo-perdida-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

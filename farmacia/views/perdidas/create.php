<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Perdidas */

$this->title = 'Nueva Pérdida';
$this->params['breadcrumbs'][] = ['label' => 'Pérdidas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perdidas-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

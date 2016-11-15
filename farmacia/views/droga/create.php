<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Droga */

$this->title = 'Nueva Droga';
$this->params['breadcrumbs'][] = ['label' => 'Drogas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="droga-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Via */

$this->title = 'Nueva Vía';
$this->params['breadcrumbs'][] = ['label' => 'Vías', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="via-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

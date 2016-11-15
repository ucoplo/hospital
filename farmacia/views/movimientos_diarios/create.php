<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Movimientos_diarios */

$this->title = 'Create Movimientos Diarios';
$this->params['breadcrumbs'][] = ['label' => 'Movimientos Diarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimientos-diarios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

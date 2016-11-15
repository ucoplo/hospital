<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Techo */

$this->title = 'Modificar Techo: ' . $model->id_techo;
$this->params['breadcrumbs'][] = ['label' => 'Techos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_techo, 'url' => ['view', 'id' => $model->id_techo]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="techo-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

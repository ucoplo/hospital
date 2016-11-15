<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Receta_electronica */

$this->title = 'Update Receta Electronica: ' . $model->RE_NRORECETA;
$this->params['breadcrumbs'][] = ['label' => 'Receta Electronicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->RE_NRORECETA, 'url' => ['view', 'id' => $model->RE_NRORECETA]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="receta-electronica-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

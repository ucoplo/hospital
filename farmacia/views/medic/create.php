<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Medic */

$this->title = 'Crear Medicamento';
$this->params['breadcrumbs'][] = ['label' => 'Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medic-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

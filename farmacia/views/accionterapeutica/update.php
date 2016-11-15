<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Accionterapeutica */

$this->title = 'Modificar Acción Terapéutica: ' . ' ' . $model->AC_COD.' - '.$model->AC_DESCRI;
$this->params['breadcrumbs'][] = ['label' => 'Acciones terapéuticas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->AC_COD, 'url' => ['view', 'id' => $model->AC_COD]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="accionterapeutica-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

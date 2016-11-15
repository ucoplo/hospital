<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Accionterapeutica */

$this->title = 'Crear Acción Terapéutica';
$this->params['breadcrumbs'][] = ['label' => 'Acciones terapéuticas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accionterapeutica-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

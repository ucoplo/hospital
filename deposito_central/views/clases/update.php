<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Clases */

$this->title = 'Modificar Clase: ' . ' ' . $model->CL_COD.' - '.$model->CL_NOM;
$this->params['breadcrumbs'][] = ['label' => 'Clases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->CL_COD, 'url' => ['view', 'id' => $model->CL_COD]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="clases-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Clases */

$this->title = 'Crear Clase';
$this->params['breadcrumbs'][] = ['label' => 'Clases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clases-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Alarma */

$this->title = 'Nueva Alarma';
$this->params['breadcrumbs'][] = ['label' => 'Alarmas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alarma-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

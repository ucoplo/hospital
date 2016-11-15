<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Deposito */

$this->title = 'Crear Depósito';
$this->params['breadcrumbs'][] = ['label' => 'Depositos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposito-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

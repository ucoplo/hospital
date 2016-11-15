<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Remito_deposito */

$this->title = 'Create Remito Deposito';
$this->params['breadcrumbs'][] = ['label' => 'Remito Depositos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remito-deposito-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

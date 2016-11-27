<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Pedido_reposicion_farmacia */

$this->title = 'Create Pedido Reposicion Farmacia';
$this->params['breadcrumbs'][] = ['label' => 'Pedido Reposicion Farmacias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedido-reposicion-farmacia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

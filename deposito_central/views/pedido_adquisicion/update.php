<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Pedido_adquisicion */

$this->title = 'Update Pedido Adquisicion: ' . $model->PE_NUM;
$this->params['breadcrumbs'][] = ['label' => 'Pedido Adquisicions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->PE_NUM, 'url' => ['view', 'id' => $model->PE_NUM]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pedido-adquisicion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

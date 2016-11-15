<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Pedido_adquisicion */

$this->title = 'Nuevo Pedido de Adquisicion';
$this->params['breadcrumbs'][] = ['label' => 'Pedidos de Adquisición', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedido-adquisicion-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

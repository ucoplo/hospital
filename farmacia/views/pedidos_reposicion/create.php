<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Pedentre */

$this->title = 'Nuevo Pedido de Reposición';
$this->params['breadcrumbs'][] = ['label' => 'Pedidos Reposición', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedentre-create">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
    

    <?= $this->render('_form', [
        'model' => $model,
        'renglones' => $renglones,
    ]) ?>

    

</div>

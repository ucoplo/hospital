<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Proveedores */

$this->title = 'Nuevo Proveedor';
$this->params['breadcrumbs'][] = ['label' => 'Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proveedores-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

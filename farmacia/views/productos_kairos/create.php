<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Productos_kairos */

$this->title = 'Create Productos Kairos';
$this->params['breadcrumbs'][] = ['label' => 'Productos Kairos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="productos-kairos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

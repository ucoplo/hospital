<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Techo */

$this->title = 'Nuevo Techo';
$this->params['breadcrumbs'][] = ['label' => 'Techos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="techo-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

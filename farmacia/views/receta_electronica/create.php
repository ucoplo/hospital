<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model farmacia\models\Receta_electronica */

$this->title = 'Create Receta Electronica';
$this->params['breadcrumbs'][] = ['label' => 'Receta Electronicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receta-electronica-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

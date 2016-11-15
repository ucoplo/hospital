<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model farmacia\models\ArticGral */

$this->title = 'Crear Artículo General';
$this->params['breadcrumbs'][] = ['label' => 'Artículos Generales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artic-gral-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

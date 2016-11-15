<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model deposito_central\models\Techo_articulo */

$this->title = 'Crear Techo Artículo';
$this->params['breadcrumbs'][] = ['label' => 'Techos Artículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="techo-articulo-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Techo_articulo */

$this->title = 'Modificar Techo Artículo: ' . $model->TA_CODSERV;
$this->params['breadcrumbs'][] = ['label' => 'Techos Artículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->TA_CODSERV, 'url' => ['view', 'TA_CODSERV' => $model->TA_CODSERV, 'TA_DEPOSITO' => $model->TA_DEPOSITO, 'TA_CODART' => $model->TA_CODART]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="techo-articulo-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

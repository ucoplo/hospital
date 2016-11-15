<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Servicio */

$this->title = $model->SE_CODIGO.' - '.$model->SE_DESCRI;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicio-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->SE_CODIGO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->SE_CODIGO], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro de eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'SE_CODIGO',
            'SE_DESCRI',
             [
                'attribute' => 'SE_TPOSER',
                'format' => 'text',
                'label' => 'Tipo',
                'value' => $model->tipo_descripcion()
             ],
            'SE_CCOSTO',
            'SE_SALA',
            'SE_AREA',
            [
                'attribute' => 'SE_INFO',
                'format' => 'text',
                'label' => 'Genera informe',
                'value' => $model->boolean_descripcion($model->SE_INFO)
             ],
        ],
    ]) ?>

</div>

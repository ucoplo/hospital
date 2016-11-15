<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use farmacia\models\Clases;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Droga */

$this->title = $model->DR_CODIGO;
$this->params['breadcrumbs'][] = ['label' => 'Drogas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="droga-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->DR_CODIGO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->DR_CODIGO], [
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
            'DR_CODIGO',
            'DR_DESCRI:ntext',
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'DR_CLASE',
                'format' => 'text',
                'label' => 'Clase',
                'value' => $model->clase->CL_NOM,
            ],
            
        ],
    ]) ?>

</div>

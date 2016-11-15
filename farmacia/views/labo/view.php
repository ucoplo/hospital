<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Labo */

$this->title = $model->LA_CODIGO."-".$model->LA_NOMBRE;
$this->params['breadcrumbs'][] = ['label' => 'Laboratorios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="labo-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->LA_CODIGO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->LA_CODIGO], [
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
            'LA_CODIGO',
            'LA_NOMBRE',
               [
                
                'attribute' => 'LA_TIPO',
                'format' => 'text',
                'label' => 'Tipo',
                'value' => $model->tipo_descripcion($model)
                              
                             
            ],
            
        ],
    ]) ?>

</div>

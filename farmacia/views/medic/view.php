<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Medic */

$this->title = $model->ME_CODIGO;
$this->params['breadcrumbs'][] = ['label' => 'Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medic-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->ME_CODIGO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->ME_CODIGO], [
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
            'ME_CODIGO',
            'ME_NOMCOM',
            'ME_CODKAI',
            'ME_CODRAF',
            'ME_KAIBAR',
            'ME_KAITRO',
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'ME_CODMON',
                'format' => 'text',
                'value' => $model->monodroga->AG_NOMBRE,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'ME_CODLAB',
                'format' => 'text',
                'value' => $model->laboratorio->LA_NOMBRE,
            ],
            
            'ME_PRES:ntext',
            'ME_FRACCQ',
            'ME_VALVEN',
            'ME_ULTCOM',
            'ME_VALCOM',
            'ME_ULTSAL',
            'ME_STMIN',
            'ME_STMAX',
            'ME_RUBRO',
            'ME_UNIENV',
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'ME_DEPOSITO',
                'format' => 'text',
                'value' => $model->deposito->DE_DESCR,
            ],
            
        ],
    ]) ?>

</div>

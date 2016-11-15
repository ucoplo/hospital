<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use farmacia\models\Deposito;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Techo */

$this->title = $model->id_techo;
$this->params['breadcrumbs'][] = ['label' => 'Techos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="techo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id_techo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_techo], [
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
           
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'TM_CODSERV',
                'format' => 'text',
                'label' => 'Servicio',
                'value' => $model->servicio->SE_DESCRI,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'TM_DEPOSITO',
                'format' => 'text',
                'label' => 'Depósito',
                'value' => $model->deposito->DE_DESCR,
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'TM_CODMON',
                'format' => 'text',
                'label' => 'Monodroga',
                'value' => $model->monodroga->AG_NOMBRE,
            ],
            'TM_CANTID',
        ],
    ]) ?>

</div>

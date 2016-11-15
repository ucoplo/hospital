<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Motivo_perdidaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Motivo de Pérdidas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motivo-perdida-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Motivo de Pérdida', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'MP_COD',
            'MP_NOM',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

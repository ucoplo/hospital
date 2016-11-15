<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\ViaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vías';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="via-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Vía', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'VI_CODIGO',
            'VI_DESCRI',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

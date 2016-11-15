<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\DepositoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Depósitos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposito-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Depósito', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'DE_CODIGO',
            'DE_DESCR',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

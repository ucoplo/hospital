<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\helpers\ArrayHelper;
use farmacia\models\Clases;

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\DrogaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Drogas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="droga-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Droga', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'DR_CODIGO',
            'DR_DESCRI:ntext',
             
             [
                'attribute' => 'DR_CLASE',
                'label' => 'Clase',
                'value' => function ($model){
                    $clase= Clases::findOne($model->DR_CLASE);
                    return $clase->CL_NOM;
                },
                'filter'=> ArrayHelper::map(Clases::find()->all(),'CL_COD','CL_NOM'),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

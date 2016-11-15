<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\ServicioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicio-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Servicio', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'SE_CODIGO',
            'SE_DESCRI',
             [
                'attribute' => 'SE_TPOSER',
                'value' => function ($model){
                    return ($model->tipo_descripcion());
                    
                },
               
            ],
            'SE_CCOSTO',
            'SE_SALA',
            // 'SE_AREA',
            // 'SE_INFO',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

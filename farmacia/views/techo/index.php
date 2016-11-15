<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use farmacia\models\Deposito;
use farmacia\models\Servicio;
use farmacia\models\ArticGral;

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\TechoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Techos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="techo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Techo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'TM_CODSERV',
                'label' => 'Servicio',
                'value' => function ($model){
                    $deposito= Servicio::findOne($model->TM_CODSERV);
                    return $deposito->SE_DESCRI;
                },
                'filter'=> ArrayHelper::map(Servicio::find()->all(),'SE_CODIGO','SE_DESCRI'),
            ],
            [
                'attribute' => 'TM_DEPOSITO',
                'label' => 'Depósito',
                'value' => function ($model){
                    $deposito= Deposito::findOne($model->TM_DEPOSITO);
                    return $deposito->DE_DESCR;
                },
                'filter'=> ArrayHelper::map(Deposito::find()->all(),'DE_CODIGO','DE_DESCR'),
            ],
            [
                'attribute' => 'TM_CODMON',
                'label' => 'Monodroga',
                'value' => function ($model){
                    $deposito= ArticGral::findOne($model->TM_CODMON);
                    return $deposito->AG_NOMBRE;
                },
                'filter'=> ArrayHelper::map(ArticGral::find()->all(),'AG_CODIGO','AG_NOMBRE'),
            ],
            
            'TM_CANTID',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Remito_depositoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Remito Depositos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remito-deposito-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Remito Deposito', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'RS_CODEP',
            'RS_NROREM',
            'RS_FECHA',
            'RS_HORA',
            'RS_CODOPE',
            // 'RS_NUMPED',
            // 'RS_SERSOL',
            // 'RS_IMPORT',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Techo_articuloSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Techos Artículos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="techo-articulo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Techo Artículo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'TA_CODSERV',
            'TA_DEPOSITO',
            'TA_CODART',
            'TA_CANTID',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

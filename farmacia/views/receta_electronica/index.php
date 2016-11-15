<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Receta_electronicaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Receta Electronicas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receta-electronica-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Receta Electronica', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'RE_NRORECETA',
            'RE_HISCLI',
            'RE_FECINI',
            'RE_FECFIN',
            'RE_MEDICO',
            // 'RE_NOTA:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

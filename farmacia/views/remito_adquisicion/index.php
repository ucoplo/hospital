<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Remito_AdquisicionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Adquisiciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remito--adquisicion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Adquisición', ['seleccion_remito_deposito'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            

            'RE_NUM',
            'RE_FECHA:date',
            'RE_HORA',
             [
                'attribute' => 'deposito',
                'value' => 'deposito.DE_DESCR',
            ],
            
            'RE_CONCEP:ntext',
            // 'RE_TIPMOV',
            // 'RE_DEPOSITO',
            // 'RE_REMDEP',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Devolucion_salas_granelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Devoluciones de Salas Granel';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-salas-granel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Devolución', ['seleccion_remito'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            

            'DE_NRODEVOL',
            'DE_FECHA',
            'DE_HORA',
            'servicio.SE_DESCRI',
            'DE_CODOPE',
            // 'DE_ENFERM',
            // 'DE_SOBRAN',
            // 'DE_NUMREMOR',
            // 'DE_DEPOSITO',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}',
            ]
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Devolucion_salasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Devoluciones de Salas Sobrantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-salas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Devolución', ['create'], ['class' => 'btn btn-success']) ?>
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

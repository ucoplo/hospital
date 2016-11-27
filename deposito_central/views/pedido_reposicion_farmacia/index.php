<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Pedido_reposicion_farmaciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pedido Reposicion Farmacias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedido-reposicion-farmacia-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pedido Reposicion Farmacia', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'PE_NROPED',
            'PE_FECHA',
            'PE_HORA',
            'PE_SERSOL',
            'PE_DEPOSITO',
            // 'PE_REFERENCIA:ntext',
            // 'PE_CLASE',
            // 'PE_SUPERV',
            // 'PE_PROCESADO',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

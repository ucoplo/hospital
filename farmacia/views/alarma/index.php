<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\AlarmaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Alarmas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alarma-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Alarma', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'monodroga.AG_NOMBRE',
                'label' => 'Medicamento',
            ],
            [
                'attribute' => 'deposito.DE_DESCR',
                'label' => 'Depósito',
            ],
            'AL_MIN',
            'AL_MAX',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

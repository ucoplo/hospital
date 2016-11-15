<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\PerdidasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pérdidas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perdidas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Pérdida', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       
        'columns' => [
           
            'PE_NROREM',
            'PE_FECHA:date',
            'PE_HORA',
           
            [
                'attribute' =>'motivo.MP_NOM',
                'label' => 'Tipo'
            ],
            [
                'attribute' =>'operador.LE_APENOM',
                'label' => 'Operador'
            ],
           

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}',
            ]
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

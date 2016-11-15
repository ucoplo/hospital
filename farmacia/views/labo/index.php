<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LaboSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laboratorios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="labo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Laboratorio', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?><?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           
            'LA_CODIGO',
            'LA_NOMBRE',
            [
                
                'attribute' => 'LA_TIPO',
                'format' => 'text',
                'label' => 'Tipo',
                'content' => function ($model, $key, $index, $column) {
                                if ($model->LA_TIPO=='i')
                                    return 'interno';
                                elseif ($model->LA_TIPO=='e')
                                    return 'externo';
                                else
                                    return 'indefinido';
                             //return Html::a('Reservations', ['reservations/grid', 'Reservation[customer_id]' => $model->id]);
                            }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?><?php Pjax::end(); ?>

</div>

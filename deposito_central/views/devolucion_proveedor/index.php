<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Devolucion_proveedorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Devoluciones a Proveedores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-proveedor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Devolución', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            

            'DD_NROREM',
            'DD_FECHA:date',
            'DD_HORA',
            
            [
                'attribute' => 'deposito',
                'value' => 'deposito.DE_DESCR',
            ],
            [
                'attribute' => 'proveedor',
                'value' => 'proveedor.PR_RAZONSOC',
            ],
            //  [
            //     'class' =>  yii\grid\DataColumn::className(), // this line is optional
            //     'attribute' => 'DD_PROVE',
            //     'format' => 'text',
            //     'value' => function ($model){return $model->descripcion_proveedor();},
            // ],
            
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}',
            ]
            
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

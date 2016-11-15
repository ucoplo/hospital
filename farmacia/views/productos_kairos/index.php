<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Productos_kairosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Productos Kairos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="productos-kairos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           

            'codigo',
            'descripcion',
            
            [
                'attribute' => 'laboratorio',
                'label' => 'Laboratorio',
                'value' => function ($model){
                     if (isset($model->laboratorio) && !empty($model->laboratorio)){
                        //$laboratorio   = Medic::findOne($model->laboratorio);
                        //return $laboratorio->descripcion;
                        return $model->laboratorio_descripcion;
                    }
                    else{
                        return '';
                    }
                    
                },
                //'filter'=> ArrayHelper::map(Medic::find()->all(),'ME_CODIGO','ME_NOMCOM'),
            ],
            //'origen',
            //'psicofarmaco',
            // 'codigo_venta',
            // 'estupefaciente',
            // 'estado',

           
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
             
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

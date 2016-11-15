<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Consumo_medicamentos_granelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planillas de Retiro a Granel';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumo-medicamentos-granel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo suministro', ['seleccion_servicio'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                'id' => $model->CM_NROREM,
                'style' =>
                    ($model->CM_PROCESADO) ? 'background-color: #85C485;' : '',
                ];
            },
        'columns' => [
           

            'CM_NROREM',
             [
                'attribute' => 'CM_SERSOL',
                'value' => 'servicio.SE_DESCRI',
                
            ],
            'CM_FECHA:date',
            'CM_HORA',
            
             [
                'attribute' => 'CM_PROCESADO',
                'value' => function($model){
                            if ($model->CM_PROCESADO){
                                return $model->operador->LE_APENOM;
                            }else{
                                return "";
                            }
                },
            ],
            //'CM_CODOPE',
            // 'CM_DEPOSITO',
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}',
            ],
            //Si no estaba procesado se podia elimianr o modificar
            /*['class' => 'yii\grid\ActionColumn',
             'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return $model->CM_PROCESADO ? false : true;
                     },
                     'delete' => function ($model, $key, $index) {
                        return $model->CM_PROCESADO ? false : true;
                     },
                ]
            ],*/

        ],
    ]); ?>
<?php Pjax::end(); ?></div>

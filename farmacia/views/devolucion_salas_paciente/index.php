<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Devolucion_salas_pacienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Devoluciones de Salas Paciente';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-salas-paciente-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Devolución', ['seleccion_vale'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       
        'columns' => [
           

            'DE_NRODEVOL',
            
            [            
                'attribute' =>'DE_HISCLI',
                'label' => 'Historia Clínica',
                'value' => function ($model){
                    return $model['DE_HISCLI'].' - '.$model->paciente->PA_APENOM;
                               
                },
            ],
            [            
                'attribute' =>'DE_FECHA',
                'label' => 'Fecha y Hora',
                'value' => function ($model){
                    return Yii::$app->formatter->asDate($model['DE_FECHA'],'php:d-m-Y').' '.$model['DE_HORA'];
                               
                },
            ],
            [
                'attribute' => 'DE_SERSOL',
                'value' => 'servicio.SE_DESCRI',
                'label' =>  'Servicio',
            ],
           
            // 'DE_CODOPE',
            // 'DE_ENFERM',
            // 'DE_UNIDIAG',
            // 'DE_NUMVALOR',
            // 'DE_DEPOSITO',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}',
            ]
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

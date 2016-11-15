<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Consumo_medicamentos_pacientesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if ($condpac=='A')
    $tipo = "Ambulatorios";
else
    $tipo = "Internados";
$this->title = 'Consumo Medicamentos Pacientes '.$tipo;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumo-medicamentos-pacientes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel, 'condpac' => $condpac]); ?>

    <p>
        <?= Html::a('Nuevo Vale', ['seleccion_servicio', 'condpac' => $condpac], ['class' => 'btn btn-success']) ?>
        
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                'id' => $model->CM_NROVAL,
                'style' =>
                    ($model->CM_PROCESADO) ? 'background-color: #85C485;' : '',
                ];
            },
        'columns' => [

            'CM_NROREM',
            'CM_NROVAL',
            'CM_HISCLI',
             [
                'attribute' => 'CM_HISCLI',
                'value' => 'paciente.PA_APENOM',
                'label' =>  'Apellido y Nombres',
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
            [
                'attribute' => 'CM_SERSOL',
                'value' => 'servicio.SE_DESCRI',
                
            ],
            // 'CM_CODOPE',
            // 'CM_UNIDIAG',
            // 'CM_CONDPAC',
            // 'CM_NROVAL',
            // 'CM_SUPERV',
            // 'CM_MEDICO',
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}',
            ],
            // ['class' => 'yii\grid\ActionColumn',
            //  'visibleButtons' => [
            //         'update' => function ($model, $key, $index) {
            //             return $model->CM_PROCESADO ? false : true;
            //          },
            //          'delete' => function ($model, $key, $index) {
            //             return $model->CM_PROCESADO ? false : true;
            //          },
            //     ]
            // ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

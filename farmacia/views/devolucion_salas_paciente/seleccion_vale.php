<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use farmacia\models\Deposito;
use yii\helpers\ArrayHelper;


 
$this->registerJs(
   "$('document').ready(function(){ 
        setInterval(function(){
            $.pjax.reload({container:'#pjax_remitos'}); 
        }, 60000);
    });"
);

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Remito_depositoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vales de Suministro Paciente';
$this->params['breadcrumbs'][] = ['label' => 'Devoluciones de Salas Paciente', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="vale-farmacia-index">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
    

    
<?php Pjax::begin(['id'=>'pjax_vales']); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => "vales_farmacia",
        'summary'=>"",
        'columns' => [
            [            
                'attribute' =>'CM_FECHA',
                'label' => 'Fecha y Hora',
                'value' => function ($model){
                    return Yii::$app->formatter->asDate($model['CM_FECHA'],'php:d-m-Y').' '.$model['CM_HORA'];
                               
                },
            ],
            [            
                'attribute' =>'CM_NROVAL',
                'label' => 'Nro Vale',
            ],
             [            
                'attribute' =>'CM_NROREM',
                'label' => 'Nro Remito',
            ],
            [            
                'attribute' =>'servicio.SE_DESCRI',
                'label' => 'Servicio',
            ],
            [            
                'attribute' =>'supervisor.LE_APENOM',
                'label' => 'Enfermero/a',
            ],
       
           
            [ 'class' => 'yii\grid\ActionColumn', 
              'template' => ' {crear}', 
              'buttons' => [ 'crear' => function ($url,$model) { 
                                return Html::a( '<span class="glyphicon glyphicon-menu-right"></span>', ['devolucion_salas_paciente/iniciar_creacion', 'vale' => $model['CM_NROVAL']], [ 'title' => 'Seleccionar', 'data-pjax' => '0', ] ); 
                             },
                           ],
            ],
                  
        ],
    ]); ?>
<?php Pjax::end(); ?>
    

</div>


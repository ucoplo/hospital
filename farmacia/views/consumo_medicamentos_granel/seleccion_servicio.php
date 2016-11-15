<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use farmacia\models\Deposito;
use yii\helpers\ArrayHelper;


 
$this->registerJs(
   "$('document').ready(function(){ 
        setInterval(function(){
            $.pjax.reload({container:'#pjax_servicios'}); 
        }, 60000);
    });"
);

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Remito_depositoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vales a Granel de Servicios Disponibles';
$this->params['breadcrumbs'][] = ['label' => 'Planillas de Retiro a Granel ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="vale-farmacia-index">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
    

    
<?php Pjax::begin(['id'=>'pjax_servicios']); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => "vales_servicios",
        'summary'=>"",
        'columns' => [
            [            
                'attribute' =>'VM_SERSOL',
                'label' => 'Código de Servicio',
            ],
            [            
                'attribute' =>'SE_DESCRI',
                'label' => 'Descripción de Servicio',
            ],
            [            
                'attribute' =>'supervisor',
                'label' => 'Enfermero/a',
            ],
            [            
                'attribute' =>'VM_FECHA',
                'label' => 'Fecha y Hora',
                'value' => function ($model){
                    return Yii::$app->formatter->asDate($model['VM_FECHA'],'php:d-m-Y').' '.$model['VM_HORA'];
                               
                },
            ],
            [ 'class' => 'yii\grid\ActionColumn', 
              'template' => ' {crear}', 
              'buttons' => [ 'crear' => function ($url,$model) { 
                                return Html::a( '<span class="glyphicon glyphicon-menu-right"></span>', ['consumo_medicamentos_granel/iniciar_creacion', 'vale' => $model['VM_NUMVALE']], [ 'title' => 'Seleccionar', 'data-pjax' => '0', ] ); 
                             },
                           ],
            ],
                  
        ],
    ]); ?>
<?php Pjax::end(); ?>
    

</div>


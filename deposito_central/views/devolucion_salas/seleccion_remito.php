<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use deposito_central\models\Deposito;
use yii\helpers\ArrayHelper;


 
$this->registerJs(
   "$('document').ready(function(){ 
        setInterval(function(){
            $.pjax.reload({container:'#pjax_remitos'}); 
        }, 60000);
    });"
);

/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Remito_depositoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Remitos de Suministro';
$this->params['breadcrumbs'][] = ['label' => 'Devoluciones de Salas Granel ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="remito-entrega-index">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
    

    
<?php Pjax::begin(['id'=>'pjax_remitos']); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => "remitos_deposito_central",
        'summary'=>"",
        'columns' => [
            [            
                'attribute' =>'PE_FECHA',
                'label' => 'Fecha y Hora',
                'value' => function ($model){
                    return Yii::$app->formatter->asDate($model['PE_FECHA'],'php:d-m-Y').' '.$model['PE_HORA'];
                               
                },
            ],
            [            
                'attribute' =>'PE_NROREM',
                'label' => 'Nro Remito',
            ],
            [            
                'attribute' =>'servicio.SE_DESCRI',
                'label' => 'Servicio',
            ],
            [            
                'attribute' =>'enfermero.LE_APENOM',
                'label' => 'Enfermero/a',
            ],
       
           
            [ 'class' => 'yii\grid\ActionColumn', 
              'template' => ' {crear}', 
              'buttons' => [ 'crear' => function ($url,$model) { 
                                return Html::a( '<span class="glyphicon glyphicon-menu-right"></span>', ['devolucion_salas/iniciar_creacion', 'remito' => $model['PE_NROREM']], [ 'title' => 'Seleccionar', 'data-pjax' => '0', ] ); 
                             },
                           ],
            ],
                  
        ],
    ]); ?>
<?php Pjax::end(); ?>
    

</div>


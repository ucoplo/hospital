<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use deposito_central\models\Deposito;
use yii\helpers\ArrayHelper;


 
$this->registerJs(
   "$('document').ready(function(){ 
        setInterval(function(){
            $.pjax.reload({container:'#pjax_servicios'}); 
        }, 60000);
    });"
);

/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Remito_depositoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pedidos de Farmacia Disponibles';
$this->params['breadcrumbs'][] = ['label' => 'Planillas de Retiro', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="pedido-insumo-index">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
    
<?php Pjax::begin(['id'=>'pjax_pedidos_farmacia']); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => "pedidos_farmacia",
        'summary'=>"",
        'columns' => [
            [            
                'attribute' =>'PE_SERSOL',
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
                'attribute' =>'PE_FECHA',
                'label' => 'Fecha y Hora',
                'value' => function ($model){
                    return Yii::$app->formatter->asDate($model['PE_FECHA'],'php:d-m-Y').' '.$model['PE_HORA'];
                               
                },
            ],
            [ 'class' => 'yii\grid\ActionColumn', 
              'template' => ' {crear}', 
              'buttons' => [ 'crear' => function ($url,$model) { 
                                return Html::a( '<span class="glyphicon glyphicon-menu-right"></span>', ['planilla_entrega/iniciar_creacion_farmacia', 'pedido' => $model['PE_NROPED']], [ 'title' => 'Seleccionar', 'data-pjax' => '0', ] ); 
                             },
                           ],
            ],
                  
        ],
    ]); ?>
<?php Pjax::end(); ?>

</div>


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

$this->title = 'Pedidos de Insumos de Servicios Disponibles';
$this->params['breadcrumbs'][] = ['label' => 'Planillas de Retiro', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="pedido-insumo-index">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
    
     <p>
        <?= Html::a('Planilla sin Pedido', ['create_sin_pedido'], ['class' => 'btn btn-success']) ?>
    </p>
    
<?php Pjax::begin(['id'=>'pjax_servicios']); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => "vales_servicios",
        'summary'=>"",
        'columns' => [
            [            
                'attribute' =>'VD_SERSOL',
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
                'attribute' =>'VD_FECHA',
                'label' => 'Fecha y Hora',
                'value' => function ($model){
                    return Yii::$app->formatter->asDate($model['VD_FECHA'],'php:d-m-Y').' '.$model['VD_HORA'];
                               
                },
            ],
            [ 'class' => 'yii\grid\ActionColumn', 
              'template' => ' {crear}', 
              'buttons' => [ 'crear' => function ($url,$model) { 
                                return Html::a( '<span class="glyphicon glyphicon-menu-right"></span>', ['planilla_entrega/iniciar_creacion', 'vale' => $model['VD_NUMVALE']], [ 'title' => 'Seleccionar', 'data-pjax' => '0', ] ); 
                             },
                           ],
            ],
                  
        ],
    ]); ?>
<?php Pjax::end(); ?>



</div>


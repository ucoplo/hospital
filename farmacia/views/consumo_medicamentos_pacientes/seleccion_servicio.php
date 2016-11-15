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
if ($condpac=='A')
    $tipo = "Ambulatorios";
else
    $tipo = "Internados";

$this->title = 'Vales de Servicios Disponibles - '. $tipo;
$this->params['breadcrumbs'][] = ['label' => 'Consumo Medicamentos Pacientes '.$tipo, 'url' => ['index','condpac'=>$condpac]];
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
                'attribute' =>'VE_SERSOL',
                'label' => 'Código de Servicio',
            ],
            [            
                'attribute' =>'SE_DESCRI',
                'label' => 'Descripción de Servicio',
            ],
            [            
                'attribute' =>'vales_pendientes',
                'label' => 'Vales Pendientes',
            ],
            [            
                'attribute' =>'ultimo_remito',
                'label' => 'Último Nro de Remito',
                'value' => function ($model){
                     if (isset($model['ultimo_remito']) && !empty($model['ultimo_remito'])){
                        return $model['ultimo_remito'];
                    }
                    else{
                        return '';
                    }
                    
                },
            ],
            [ 'class' => 'yii\grid\ActionColumn', 
              'template' => ' {crear}', 
              'buttons' => [ 'crear' => function ($url,$model) { 
                                return Html::a( '<span class="glyphicon glyphicon-menu-right"></span>', ['consumo_medicamentos_pacientes/create', 'servicio' => $model['VE_SERSOL'], 'condpac' => $model['VE_CONDPAC']], [ 'title' => 'Seleccionar', 'data-pjax' => '0', ] ); 
                             },
                           ],
            ],
                  
        ],
    ]); ?>
<?php Pjax::end(); ?>
    

</div>


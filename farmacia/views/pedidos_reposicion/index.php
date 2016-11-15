<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Pedidos_adquisicionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pedidos de Reposición';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedentre-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>
        <?= Html::a('Nuevo Pedido', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'columns' => [
            

            'PE_NROPED',
            
            [
                            'attribute' => 'PE_FECHA',
                            'format' => ['date', 'php:d/m/Y']
                        ],       
          
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'PE_DEPOSITO',
                'format' => 'text',
                'value' => function ($model){return $model->pEDEPOSITO->DE_DESCR;},
            ],
            'PE_REFERENCIA',
            
            // 'PE_DEPOS',
            //'PE_PROCESADO',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}{delete}',
             'visibleButtons' => [
                    'delete' => function ($model, $key, $index) {
                        return ($model->PE_PROCESADO == 'T') ? false : true;
                     },
                ]
            ],
            
                
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

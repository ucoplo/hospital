<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use farmacia\models\Deposito;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Remito_depositoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Remitos de Depósito Disponibles';
$this->params['breadcrumbs'][] = ['label' => 'Remitos de Adquisición', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="remito-deposito-index">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>"",
        'columns' => [
            

            
            'RS_NROREM',
            'RS_FECHA:date',
            'RS_HORA',
            
             [
                'attribute' => 'RS_CODEP',
                'label' => 'Depósito destino',
                'value' => function ($model){
                    $deposito= Deposito::findOne($model->RS_CODEP);
                    return $deposito->DE_DESCR;
                },
                'filter'=> ArrayHelper::map(Deposito::find()->all(),'DE_CODIGO','DE_DESCR'),
            ],
            'RS_CODOPE',
            'RS_NUMPED',
            // 'RS_SERSOL',
            // 'RS_IMPORT',
            [ 'class' => 'yii\grid\ActionColumn', 
              'template' => ' {remito_adquisicion/create_remito_deposito}', 
              'buttons' => [ 'remito_adquisicion/create_remito_deposito' => function ($url) { 
                                return Html::a( '<span class="glyphicon glyphicon-menu-right"> </span>', $url, [ 'title' => 'Seleccionar', 'data-pjax' => '0', ] ); 
                             },
                           ],
            ],
            
        ],
    ]); ?>
<?php Pjax::end(); ?>
    <p>
        <?= Html::a('Origen Externo', ['create_externo'], ['class' => 'btn btn-success']) ?>
    </p>

</div>


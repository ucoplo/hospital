<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Pedido_adquisicionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pedidos de Adquisición';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedido-adquisicion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Pedido', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'PE_NUM',
            'PE_FECHA:date',
            'PE_HORA',
            'PE_COSTO',
            'PE_REFERENCIA:ntext',
            // 'PE_NROEXP',
            // 'PE_FECADJ',
            // 'PE_DEPOSITO',
            // 'PE_ARTDES',
            // 'PE_ARTHAS',
            // 'PE_CLASES',
            // 'PE_TIPO',
            // 'PE_EXISACT',
            // 'PE_PEDPEND',
            // 'PE_PONDHIS',
            // 'PE_PONDPUN',
            // 'PE_CLASABC',
            // 'PE_DIASABC',
            // 'PE_DIASPREVIS',
            // 'PE_DIASDEMORA',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

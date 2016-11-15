<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\OrdenCompraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orden Compras';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orden-compra-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    

    <p>
        <?= Html::a('Create Orden Compra', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?> 
<?php if (isset($orden)){ ?>   
    <?= DetailView::widget([
            'model' => $orden,
            'attributes' => [
                'OC_NRO',
                'OC_PROVEED',
                'OC_FECHA',
                'OC_FINALIZADA',
                'OC_PEDADQ',
            ],
    ]) ?>
<?php } ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'numero',
            'ejercicio',
            'OC_PROVEED',
            'OC_FECHA',
            'OC_FINALIZADA',
            // 'OC_PEDADQ',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\ProveedoresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proveedores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proveedores-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Proveedor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'PR_CODIGO',
            'PR_RAZONSOC',
            'PR_CONTACTO',
            'PR_TELEF',
            'PR_EMAIL:email',
            // 'PR_CODRAFAM',
            // 'PR_OBS:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

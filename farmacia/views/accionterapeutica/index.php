<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccionterapeuticaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acciones terapéuticas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accionterapeutica-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Acción terapéutica', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'AC_COD',
            'AC_DESCRI',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

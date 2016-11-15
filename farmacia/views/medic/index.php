<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\MedicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Medicamentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medic-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Medicamento', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ME_CODIGO',
            'ME_NOMCOM',
            'ME_CODKAI',
            'ME_CODRAF',
            'ME_KAIBAR',
            // 'ME_KAITRO',
            // 'ME_CODMON',
            // 'ME_CODLAB',
            // 'ME_PRES:ntext',
            // 'ME_FRACCQ',
            // 'ME_VALVEN',
            // 'ME_ULTCOM',
            // 'ME_VALCOM',
            // 'ME_ULTSAL',
            // 'ME_STMIN',
            // 'ME_STMAX',
            // 'ME_RUBRO',
            // 'ME_UNIENV',
            // 'ME_DEPOSITO',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

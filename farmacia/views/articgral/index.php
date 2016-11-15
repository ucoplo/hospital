<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use farmacia\models\Medic;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\ArticGralSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Artículos Generales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artic-gral-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Artículo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?><?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'AG_CODIGO',
            'AG_NOMBRE',
           
            [
                'attribute' => 'AG_CODMED',
                'label' => 'Medicamento',
                'value' => function ($model){
                     if (isset($model->AG_CODMED) && !empty($model->AG_CODMED)){
                        $medicamento   = Medic::findOne($model->AG_CODMED);
                        $nombre_medicamento = (isset($medicamento))?$medicamento->ME_NOMCOM:'';
                        return $nombre_medicamento;
                    }
                    else{
                        return '';
                    }
                    
                },
                'filter'=> ArrayHelper::map(Medic::find()->all(),'ME_CODIGO','ME_NOMCOM'),
            ],
            'AG_PRES:ntext',
            'AG_STACT',
            // 'AG_STACDEP',
            // 'AG_CODCLA',
            // 'AG_FRACCQ',
            // 'AG_PSICOF',
            // 'AG_PTOMIN',
            // 'AG_FPTOMIN',
            // 'AG_PTOPED',
            // 'AG_FPTOPED',
            // 'AG_PTOMAX',
            // 'AG_FPTOMAX',
            // 'AG_CONSDIA',
            // 'AG_FCONSDI',
            // 'AG_RENGLON:ntext',
            // 'AG_PRECIO',
            // 'AG_REDOND',
            // 'AG_PUNTUAL',
            // 'AG_FPUNTUAL',
            // 'AG_REPAUT',
            // 'AG_ULTENT',
            // 'AG_ULTSAL',
            // 'AG_UENTDEP',
            // 'AG_USALDEP',
            // 'AG_PROVINT',
            // 'AG_ACTIVO',
            // 'AG_VADEM',
            // 'AG_ORIGUSUA',
            // 'AG_FRACSAL',
            // 'AG_DROGA',
            // 'AG_VIA',
            // 'AG_DOSIS',
            // 'AG_ACCION',
            // 'AG_VISIBLE',
            // 'AG_DEPOSITO',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?><?php Pjax::end(); ?>
</div>

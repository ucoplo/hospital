<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Ambulatorios_ventanillaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vales Ambulatorios Ventanilla';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ambulatorios-ventanilla-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo Vale', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            
            'AM_NUMVALE',
            'AM_FECHA:date',
            'AM_HORA',
        'AM_HISCLI',
            [
                'attribute' => 'AM_HISCLI',
                'value' => 'paciente.PA_APENOM',
            ],
            [
                'attribute' => 'AM_PROG',
                'value' => 'programa.PR_NOMBRE',
            ],
            
            // 'AM_ENTIDER',
            // 'AM_MEDICO',
            // 'AM_DEPOSITO',
            // 'AM_FARMACEUTICO',

           ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}',
            ]
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

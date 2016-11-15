<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\PacienteBuscar */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="paciente-index">
    <?= GridView::widget([
        'options' => [
            'id' => 'gridPacientes',
            ],
        'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                'id' => $model->PA_NUMDOC,
                'style' => "cursor: pointer",
                'onclick' => 
                'window.location = "index.php?r=paciente/update&PA_HISCLI=' . $model->PA_HISCLI . '&PA_NUMDOC=' . $model->PA_NUMDOC . '&PA_TIPDOC=' . urlencode($model->PA_TIPDOC) . '"',
                ];
            },
        'dataProvider' => $dataProvider,
        'columns' => [

            'PA_APENOM',
            [
                'attribute' => 'PA_NUMDOC',
                'value' => function($model) {
                    return $model->PA_TIPDOC . ' ' . $model->PA_NUMDOC;
                }
            ],
            'PA_FECNAC:date',
            'PA_APEMA',
            //'PA_HISCLI',
            'PA_DIREC',
            //'PA_CODLOC',
            //'PA_CODPRO',
            //'PA_TELEF',
            //'PA_CODOS',
            //'PA_NROAFI',
            //'PA_CODPAIS',
            ],
    ]); ?>
</div>

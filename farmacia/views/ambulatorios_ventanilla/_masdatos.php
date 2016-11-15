<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Ambulatorios_ventanilla */

?>
<div class="ambulatorios-ventanilla-masdatos">

    <?= DetailView::widget([
        'model' => $paciente,
        'attributes' => [
            
            [
            'attribute'=>'nacionalidad.NA_DETALLE',
            'label' => 'Nacionalidad',
            ],
            [
            'attribute'=>'provincia.PR_DETALLE',
            'label' => 'Provincia',
            ],
            [
            'attribute'=>'partido.PT_DETALLE',
            'label' => 'Partido',
            ],
            [
            'attribute'=>'localidad.LO_DETALLE',
            'label' => 'Localidad',
            ],
            'PA_DIREC',
             'PA_TELEF',
         
            
        ],
    ]) ?>

</div>

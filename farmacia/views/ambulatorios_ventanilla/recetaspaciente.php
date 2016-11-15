<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Ambulatorios_ventanilla */

$this->title = "Recetas";
$this->params['breadcrumbs'][] = ['label' => 'Ambulatorios Ventanillas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ambulatorios-ventanilla-masdatos">

    <h3><?= Html::encode($this->title) ?></h3>


 
  <?= GridView::widget([
        'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                'id' => $model->RE_NRORECETA,
                'style' => "cursor: pointer",
                'onclick' => 
                'cargarRenglonesVale("' . $model->RE_NRORECETA . '",'.$model->RE_HISCLI.',"'.$model->RE_MEDICO.'");'];
            },
        'dataProvider' => $dataProvider,
         'columns' => [
            'RE_FECINI:date',
            
           'RE_FECFIN:date',
           
            [
                'attribute' => 'RE_MEDICO',
                'label' => 'Médico',
            ],
            [
                'attribute' => 'RE_NRORECETA',
                'label' => 'Diagnóstico',
            ],
          
        ],
    ]); ?>

</div>

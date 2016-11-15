<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Ambulatorios_ventanilla */

$this->title = "Historial de Retiro por Ventanilla";
$this->params['breadcrumbs'][] = ['label' => 'Ambulatorios Ventanillas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ambulatorios-ventanilla-masdatos">

    <h3><?= Html::encode($this->title) ?></h3>



  <?= GridView::widget([
        'dataProvider' => $dataProvider,
         'columns' => [
            [
                'attribute' => 'fecha',
                'label' => 'Fecha',
            ],
            [
                'attribute' => 'AM_HORA',
                'label' => 'Hora',
            ],
            [
                'attribute' => 'AM_CODMON',
                'label' => 'Código',
            ],
            [
                'attribute' => 'AG_NOMBRE',
                'label' => 'Descripción',
            ],
            [
                'attribute' => 'AM_CANTENT',
                'label' => 'Retirado',
            ],
            [
                'attribute' => 'AM_CANTPED',
                'label' => 'Pedido',
            ],
        ],
    ]); ?>

</div>

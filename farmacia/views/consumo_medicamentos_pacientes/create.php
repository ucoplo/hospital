<?php

use yii\helpers\Html;
use farmacia\assets\Vale_FarmaciaAsset;
use kartik\dialog\Dialog;

echo Dialog::widget();

Vale_FarmaciaAsset::register($this);

/* @var $this yii\web\View */
/* @var $model farmacia\models\Consumo_medicamentos_pacientes */
if ($model->CM_CONDPAC=='I'){
    $tipo = "Internados";
    $vista = '_form_internados';
}
else{
    $tipo = "Ambulatorios";
    $vista = '_form_ambulatorios';
}

$this->title = 'Nuevo Vale de Farmacia - '.$tipo;
$this->params['breadcrumbs'][] = ['label' => 'Consumo Medicamentos Pacientes '.$tipo, 'url' => ['index','condpac'=>$model->CM_CONDPAC]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumo-medicamentos-pacientes-create">

    <h2><?= Html::encode($this->title) ?></h2>
 
    <?= $this->render($vista, [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'numero_remito' => $numero_remito,
    ]) ?>

</div>

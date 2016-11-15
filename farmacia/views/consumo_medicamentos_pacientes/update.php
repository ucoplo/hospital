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

$this->title = 'Modificar Vale Farmacia '.$tipo.' : '. $model->CM_NROVAL;
$this->params['breadcrumbs'][] = ['label' => 'Consumo Medicamentos Pacientes '.$tipo, 'url' => ['index','condpac'=>$model->CM_CONDPAC]];
$this->params['breadcrumbs'][] = ['label' => $model->CM_NROVAL, 'url' => ['view', 'id' => $model->CM_NROVAL]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumo-medicamentos-pacientes-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form_update', [
        'model' => $model,
        
    ]) ?>

</div>

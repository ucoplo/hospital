<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Paciente */

$this->title = '[' .$model->PA_TIPDOC . ' ' . $model->PA_NUMDOC . '] ' . $model->PA_APENOM;
$this->params['breadcrumbs'][] = ['label' => 'Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paciente-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'PA_TIPDOC' => $model->PA_TIPDOC, 'PA_NUMDOC' => $model->PA_NUMDOC, 'PA_HISCLI' => $model->PA_HISCLI], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'PA_TIPDOC' => $model->PA_TIPDOC, 'PA_NUMDOC' => $model->PA_NUMDOC, 'PA_HISCLI' => $model->PA_HISCLI], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro de eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'PA_APENOM',
            'PA_NOMBRE',
            'PA_APELLIDO',
            'PA_NOMELEG',
            'PA_FECNAC:date',
            [
                'attribute' => 'PA_SEXO',
                'value'=> $model->PA_SEXO == "M" ? "Masculino" : "Femenino",
            ],
            'PA_TIPDOC',
            'PA_NUMDOC',
            'PA_HISCLI',
            'nacionalidad.NA_DETALLE',
            'pais.PA_DETALLE',
            'provincia.PR_DETALLE',
            'localidad.LO_DETALLE',
            'partido.PT_DETALLE',
            'PA_DIREC',
            'calle.CA_NOM',
            'PA_NROCALL',
            'PA_BARRIO',
            'PA_CUERPO',
            'PA_PISO',
            'PA_DPTO',
            'tipoVivienda.TV_DETALLE',
            'PA_TELEF',
            'PA_EMAIL:email',
            'PA_OBSERV:ntext',
            'PA_NIVEL',
            'PA_VENNIV',
            'obraSocial.OB_NOM',
            'PA_NROAFI',
            'PA_ADEU',
            'PA_ENTDE',
            'localidadNacimiento.LO_DETALLE',
            'PA_APEMA',
            'PA_UBIC',
            'PA_USANIT',
            'PA_MEDDER',
            'PA_APEMEDD',
            'PA_ASOCIAD',
            'PA_NIVINST',
            'PA_SITLABO',
            'PA_OCUPAC',
            'PA_APEFA',
            'PA_TELFA',
            'PA_FALLEC',
            'PA_EMPEMPL',
            'PA_EMPDIR',
            'PA_CUITEMP',
            [
                'attribute' => 'PA_REGISTRADO',
                'value'=> $model->PA_REGISTRADO == "T" ? "Sí" : "No",
            ],
            'PA_USANITSU',
            'PA_ART',
        ],
    ]) ?>

</div>

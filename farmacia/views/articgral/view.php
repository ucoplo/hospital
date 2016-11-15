<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model farmacia\models\ArticGral */

$this->title = $model->AG_CODIGO;
$this->params['breadcrumbs'][] = ['label' => 'Artículos Generales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artic-gral-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Modificar', ['update', 'AG_CODIGO' => $model->AG_CODIGO,'AG_DEPOSITO' => $model->AG_DEPOSITO], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'AG_CODIGO' => $model->AG_CODIGO,'AG_DEPOSITO' => $model->AG_DEPOSITO], [
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
            'AG_CODIGO',
            'AG_NOMBRE',
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AG_CODMED',
                'format' => 'text',
                'label' => 'Medicamento',
                'value' => $model->descripcion_medic(), 
            ],
            'AG_PRES:ntext',
            'AG_STACT',
            'AG_STACDEP',
            'AG_CODCLA',
             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AG_CODCLA',
                'format' => 'text',
                'value' => $model->clase->CL_NOM,
            ],
            [
                'attribute' => 'AG_FRACCQ',
                'format' => 'text',
                'value' => $model->boolean_descripcion($model->AG_FRACCQ)
            ],
            [
                'attribute' => 'AG_PSICOF',
                'format' => 'text',
                'value' => $model->boolean_descripcion($model->AG_PSICOF)
            ],
            
            'AG_PTOMIN',
            'AG_FPTOMIN',
            'AG_PTOPED',
            'AG_FPTOPED',
            'AG_PTOMAX',
            'AG_FPTOMAX',
            'AG_CONSDIA',
            'AG_FCONSDI',
            'AG_RENGLON:ntext',
            'AG_PRECIO',
            'AG_REDOND',
            'AG_PUNTUAL',
            'AG_FPUNTUAL',
            [
                'attribute' => 'AG_REPAUT',
                'format' => 'text',
                'value' => $model->boolean_descripcion($model->AG_REPAUT)
            ],
            'AG_ULTENT:date',
            'AG_ULTSAL:date',
            'AG_UENTDEP:date',
            'AG_USALDEP:date',
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AG_PROVINT',
                'format' => 'text',
                'value' => $model->aGPROVINT->SE_DESCRI,
            ],
            [
                'attribute' => 'AG_ACTIVO',
                'format' => 'text',
                'value' => $model->boolean_descripcion($model->AG_ACTIVO)
            ],
            [
                'attribute' => 'AG_VADEM',
                'format' => 'text',
                'value' => $model->boolean_descripcion($model->AG_VADEM)
            ],
            
            'AG_ORIGUSUA',
            
            [
                'attribute' => 'AG_FRACSAL',
                'format' => 'text',
                'value' => $model->boolean_descripcion($model->AG_FRACSAL)
            ],

            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AG_DROGA',
                'format' => 'text',
                'value' => $model->aGDROGA->DR_DESCRI,
            ],

             [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AG_VIA',
                'format' => 'text',
                'value' => $model->aGVIA->VI_DESCRI,
            ],
            'AG_DOSIS',
            
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AG_ACCION',
                'format' => 'text',
                'value' => $model->aGACCION->AC_DESCRI,
            ],
            [
                'attribute' => 'AG_VISIBLE',
                'format' => 'text',
                'value' => $model->boolean_descripcion($model->AG_VISIBLE)
            ],
            [
                'class' =>  yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'AG_DEPOSITO',
                'format' => 'text',
                'value' => $model->aGDEPOSITO->DE_DESCR,
            ],
            
        ],
    ]) ?>

</div>

<?php

use farmacia\models\TipoDocumento;

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\widgets\MaskedInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Json;


/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\PacienteBuscar */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pacientes';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider->prepare(); // 
$pagActual = $dataProvider->getPagination()->getPage();
$pagTotales = $dataProvider->getPagination()->getPageCount();

?>
<div class="paciente-index">

    <h1><?= Html::encode($this->title) ?></h1>
     <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php $form = ActiveForm::begin([
        'id' => 'formBuscarPaciente',
        'action' => Url::to(['/paciente/index']),
        'method' => 'post',
        'layout' => 'horizontal',
    ]); ?>
        <div class="row">
            <input type="hidden" name="pagina" id="paginaPacientes" value=<?php echo $pagActual; ?>>
            <div class="col-md-4">
                <?php 
                    $tiposDeDocumento = TipoDocumento::listaTiposDocumento();
                ?>
                <?= $form->field($searchModel, 'PA_TIPDOC', 
                    ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
                    ->dropDownList($tiposDeDocumento, ['prompt' => '' ]); ?>
            </div>
            

            <div class="col-md-4">
                <?= $form->field($searchModel, 'PA_NUMDOC', 
                    ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
                ->widget(MaskedInput::className(), ['mask' => '99999999']) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($searchModel, 'PA_HISCLI', 
                    ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']]) ?>
            </div>
        </div>

        <div class="row"><div class="col-md-12">
            <?= $form->field($searchModel, 'PA_APENOM', 
                ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-9']]) ?>
        </div></div>


        <div class="row">
            <div class="col-md-offset-10">
            <button type="button" class="showModalButton btn btn-primary" id="btnBuscar" onclick="buscarPaciente();">Buscar</button>
        </div>

    <?php ActiveForm::end(); ?>

    <div class="row">
        <div class="col-md-offset-1">
            <ul class="pagination">
                <li <?php echo ($pagActual === 0) ? 'class="prev disabled"' : 'class="prev"'; ?> onclick="cambiarPagina(-1);">
                    <span class="glyphicon glyphicon-triangle-left"></span>
                </li>
                <li><a>página <?php echo $pagActual+1; ?> de <?php echo $pagTotales; ?></a></li>
                <li <?php echo ($pagActual === ($pagTotales-1)) ? 'class="next disabled"' : 'next'; ?> onclick="cambiarPagina(1);">
                    <span class="glyphicon glyphicon-triangle-right"></span>
                </li>
            </ul>
        </div>
    </div>

    <?php Pjax::begin(); ?><?= GridView::widget([
        //'filterModel' => $searchModel,
        'layout' => "{summary}\n{items}",
        //'filterModel' => $searchModel,
        'options' => [
            'id' => 'gridPacientes',
            ],
        'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                'id' => $model->PA_NUMDOC,
                'style' => "cursor: pointer",
                'onclick' => 
                'cargarDatosPaciente("' . $model->PA_APENOM . '","' . $model->sexo . '","' 
                . $model->PA_TIPDOC . '","' . $model->PA_NUMDOC . '","' .$model->PA_FECNAC . '","' 
                . $model->PA_HISCLI . '","' .$model->PA_DIREC . '","' 
                 .$model->PA_TELEF . '","'  . $model->PA_CODOS . '","' . $model->obraSocialDescripcion . '","' 
                . rawurlencode(preg_replace( "/\r\n|\r|\n/", "<br>", $model->obraSocialMensaje)) . '","' 
                . $model->PA_NROAFI . '","' . $model->PA_ENTDE . '","'
                . $model->PA_NIVEL . '","' . $model->PA_LOCNAC . '","' . $model->localidadNacimientoDescripcion . '","'
                . $model->PA_VENNIV . '","' . $model->PA_OBSERV . '","'
                .((isset($model->PA_NACION)&&!empty($model->PA_NACION)) ? $model->nacionalidad->NA_DETALLE:"") .'","' 
                .((isset($model->PA_CODPRO)&&!empty($model->PA_CODPRO)) ? $model->provincia->PR_DETALLE:"") .'","'
                . ((isset($model->PA_CODPAR)&&!empty($model->PA_CODPAR)) ? $model->partido->PT_DETALLE:"") .'","' 
                . ((isset($model->PA_CODLOC)&&!empty($model->PA_CODLOC)) ? $model->localidad->LO_DETALLE:"") .'");'];
            },
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
    ]); ?><?php Pjax::end(); ?>

</div>

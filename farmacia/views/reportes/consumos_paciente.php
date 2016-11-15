<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Consumos Pacientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumos-monodroga-index">
 <h3><?= Html::encode($this->title) ?></h3>
<div class="consumos-monodroga-search">

    <?php $form = ActiveForm::begin([
        'action' => ['consumos_paciente'],
        'method' => 'get',
    ]); ?>
    
    <?php
            $layout3 = <<< HTML
                <span class="input-group-addon">Desde: </span>
                {input1}
                <span class="input-group-addon">Hasta: </span>
                {input2}
                <span class="input-group-addon kv-date-remove">
                    <i class="glyphicon glyphicon-remove"></i>
                </span>
HTML;
?>
    <div class="row">
        <div class="col-md-6">
       <label class="control-label">Fecha</label>

        <?= DatePicker::widget([
                'type'=>DatePicker::TYPE_RANGE,
                'model' => $searchModel,
                'disabled' => !$filtro,
                /*'options' => ['placeholder' => 'Desde'],
                'options2' => ['placeholder' => 'Hasta'],*/
                'attribute' => 'periodo_inicio',
                'attribute2' => 'periodo_fin',
                'layout' => $layout3,
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'autoclose'=>true
                ]
            ]);
        ?>
        <div class="help-block"></div>
        
        </div>
    </div>

    <?php  // Get the initial city description
    $url = \yii\helpers\Url::to(['pacientelist']);
     
    echo $form->field($searchModel, 'hiscli')->widget(Select2::classname(), [
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Esperando resultados...'; }"),
            ],
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
             'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
             'templateResult' => new JsExpression('function(paciente) { return paciente.text; }'),
             'templateSelection' => new JsExpression('function (paciente) { return paciente.text; }'),
        ],
    ]);
    ?>

    <?= $form->field($searchModel, 'monodroga')->widget(Select2::classname(), [
        'data' => $searchModel->listaMonodrogas,
        'disabled' => !$filtro,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

     <?= $form->field($searchModel, 'clases')->widget(Select2::classname(), [
        'data' => $searchModel->listaClases,
        'disabled' => !$filtro,
        'options' => ['placeholder' => '','multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

     

    <div class="form-group">
        <?php if ($filtro) {echo Html::submitButton('Filtrar', ['class' => 'btn btn-primary']);} ?>
     
    </div>
    

    <?php ActiveForm::end(); ?>

</div>
    
   

<?php 
   if (!$filtro){ 
        
        $pdfHeader = [
            'L' => [
                'content' => '',
                 'font-size' => 15,
                'color' => '#377333'
            ],
            'C' => [
                'content' => " <div><img src='images/header_label.png' alt=''></div>
                <div>Consumos Pacientes</div>",
                'font-size' => 15,
                'color' => '#333333',
                'height'=> '150px',
                'display' => 'block'
            ],
            'R' => [
                'content' => '',
                'font-size' => 15,
                'color' => '#333333'
            ],
           // 'line' => true,
        ];
        $pdfFooter = [
            'L' => [
                'content' => 'Farmacia '.date("d-m-Y h:i"),
                'font-size' => 8,
                'font-style' => 'B', 
                'color' => '#999999'
            ],
            'R' => [
                'content' => '[ {PAGENO} ]',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' => 'serif',
                'color' => '#333333'
            ],
            'line' => true,
        ];
?>
<div class="form-group">
    
        <?= Html::a('Volver',['consumos_paciente'],array('class'=>'btn btn-primary'));?>
    </div>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'showPageSummary' => true,
        'pjax'=>true,
        // 'striped'=>true,
        // 'hover'=>true,
      
        'panel'=>['type'=>'primary', 'heading'=>'Consumos Pacientes'],
        'toolbar'=>[
            '{export}',
            '{toggleData}'
        ],
        'export' => ['messages' => ['confirmDownload'=>'¿Exporta el listado?',
                                    
                                    'downloadProgress'=>'Generando. Por favor espere...',

                                    ],
                    'header' => '',
        
        ],
        'exportConfig'=>[
             GridView::CSV => [
                'filename' => 'consumo_paciente',
            ],
            GridView::PDF => [
                   
                    'filename' => 'consumo_paciente',
                    'config' => [
                        'marginTop' => 53,
                        'cssInline' =>'.kv-heading-1{font-size:18px;color:red;}',
                        'format' => 'A4-P',
                        'methods' => [
                            'SetHeader' => [
                                ['odd' => $pdfHeader, 'even' => $pdfHeader]
                            ],
                            'SetFooter' => [
                                ['odd' => $pdfFooter, 'even' => $pdfFooter]
                            ],
                        ],
                    ]
                ],
            ],
        'columns' => [
            [
                'attribute'=>'interna', 
                'label' => 'Interna',
                'group'=>true,
                'groupedRow'=>true, 
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', 
                 'value'=>function ($model, $key, $index, $widget) {
                    $resultado = '';
                    $resultado .= (isset($model['in_hiscli']))?"Paciente: ".$model['in_hiscli']." - ".$model['pa_apenom'].' ':'';
                    $resultado .= (isset($model['in_sala']))?" Sala: ".$model['in_sala']:'';

                    return $resultado;
                },
            ],
           
            [
                'attribute'=>'interna', 
                'label' => 'Interna',
                'group'=>true,
                'groupedRow'=>true,  
                 'value'=>function ($model, $key, $index, $widget) { 
                    return "Servicio: ".$model['in_serint'].'   '."U.D: ".$model['IN_UNDIAG'];
                },
            ],
            [
                'attribute'=>'interna', 
                'label' => 'Interna',
                'group'=>true,
                'groupedRow'=>true,  
                 'value'=>function ($model, $key, $index, $widget) {
                    $resultado = '';
                    $resultado .= (isset($model['IN_FECING']))?"Fecha Ingreso: ".Yii::$app->formatter->asDate($model['IN_FECING'],'php:d-m-Y'):'';
                    $resultado .= (isset($model['IN_FECEGR']))?"Fecha Egreso: ".Yii::$app->formatter->asDate($model['IN_FECEGR'],'php:d-m-Y'):'';
                    $resultado .= (isset($model['IN_CODOS']))?"Obra Social: ".$model['IN_CODOS']:'';

                    return $resultado;
                },
            ],
            [
                'attribute'=>'interna', 
                'label' => 'Interna',
                'group'=>true,
                'groupedRow'=>true,  
                 'value'=>function ($model, $key, $index, $widget) {
                    $resultado = '';
                    $resultado .= (isset($model['IN_DIAG1']))?"Obra Social: ".$model['IN_DIAG1']:'';

                    return $resultado;
                },
            ],
            
            [
                'attribute'=>'fecha', 
                'label' => 'Fecha',
                'format'=>['date', 'php:d-m-Y'], 
                 'width'=>'50px',
            ],
            [
                'attribute'=>'hora', 
                'label' => 'Hora',
                 'width'=>'50px',
            ],
             [
                'attribute'=>'codmon', 
                'label' => "Código",
            ],
            [
                'attribute' => 'AG_NOMBRE',
                'label' => "Denominación",
            ],
             [
                'attribute'=>'total_consumo', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Cantidad",
                'pageSummary'=>true,
            ],
             [
                'attribute'=>'valor_total', 
               'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'150px', 
                'label' => "Valor Compra",
                'pageSummary'=>true,
            ],
          
             
            
        ],
    ]); ?>
<?php Pjax::end(); }?></div>

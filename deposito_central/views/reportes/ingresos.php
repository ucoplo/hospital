<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use yii\widgets\DetailView;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel deposit_central\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ingresos por Artículo';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimientos-diarios-index"><?php
    if ($filtro){?>
 <h3><?= Html::encode($this->title) ?></h3>
<div class="ingresos-articulo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['ingresos'],
        'method' => 'post',
        'layout' => 'horizontal'
    ]); ?>
    
    <?= $form->field($searchModel, 'deposito')->dropDownList($searchModel->listaDepositos, ['prompt' => 'Seleccione Depósito']);?>

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
            <label class="control-label col-sm-3">Fecha</label>
    
        <div class="col-md-9 ">
            <?= DatePicker::widget([
                    'type'=>DatePicker::TYPE_RANGE,
                    'model' => $searchModel,
                    'attribute' => 'fecha_inicio',
                    'attribute2' => 'fecha_fin',
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
    <br>
     <?php  $url_busqueda_articulos = \yii\helpers\Url::to(['reportes/buscar-articulos']);
     
            $articuloNombre = empty($searchModel->articulo) ? '' : "[$searchModel->articulo]".ArticGral::findOne($searchModel->articulo)->AG_NOMBRE;
    ?>
     <?= $form->field($searchModel, 'articulo')->widget(Select2::classname(), [
        //'initValueText' => 'aaaaaaaaa', // set the initial display text
        'options' => ['placeholder' => ''],
        //'data' => (!empty($searchModel->articulo))?[ "{$searchModel->articulo}" => "[{$searchModel->articulo}] ".$searchModel->articulo]:[],

        'pluginOptions' => [
            'ajax' => [
                'url' => $url_busqueda_articulos,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) {
                  return {q:params.term};
                }')
            ],
            'allowClear' => true,
            'enableEmpty' => true,
            'minimumInputLength' => 1,
            'language' => 'es',
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(articulo) { return articulo.text; }'),
            'templateSelection' => new JsExpression('function (articulo) { return articulo.text; }'),
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

     <?php  $url_busqueda_proveedores = \yii\helpers\Url::to(['reportes/buscar-proveedores']);
    ?>
     <?= $form->field($searchModel, 'proveedor')->widget(Select2::classname(), [
        'options' => ['placeholder' => ''],

        'pluginOptions' => [
            'ajax' => [
                'url' => $url_busqueda_proveedores,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) {
                  return {q:params.term};
                }')
            ],
            'allowClear' => true,
            'enableEmpty' => true,
            'minimumInputLength' => 1,
            'language' => 'es',
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(articulo) { return articulo.text; }'),
            'templateSelection' => new JsExpression('function (articulo) { return articulo.text; }'),
        ],
     
    ]);?>

    <?= $form->field($searchModel, 'agrupado')->radioList(array('A' => 'Artículo', 'P' => 'Proveedor')); ?>

    <div class="row">
        <div class="col-md-2 col-md-offset-10 text-right"><?php
        if($filtro) {
            echo Html::submitButton('Buscar...', ['class' => 'btn btn-primary']);
        }?>
        </div>
    </div>

        

    <?php ActiveForm::end(); ?>

</div>
    
   

<?php 
    }
   if (!$filtro){ 
        
        
        ?>
        <div class="form-group">
            <?= Html::a('Nueva Búsqueda',['ingresos'], ['class' => 'btn btn-success']);?>
            <?= Html::a('Modificar Filtro', 'javascript:history.go(-1)', ['class' => 'btn btn-primary']);?>
        </div>
        <h3>Datos del Filtro</h3>
        <?php $filtro_consulta=DetailView::widget([
            'model' => $searchModel,
            'attributes' => [
              
                 [
                    'class' =>  yii\grid\DataColumn::className(), // this line is optional
                    'attribute' => 'deposito',
                    'format' => 'text',
                    'value' => $searchModel->deposito_descripcion,
                ],
                'fecha_inicio',
                'fecha_fin',
                [
                    'class' =>  yii\grid\DataColumn::className(), // this line is optional
                    'attribute' => 'articulo',
                    'format' => 'text',
                    'value' => $searchModel->articulo_descripcion,
                ],
                [
                    'class' =>  yii\grid\DataColumn::className(), // this line is optional
                    'attribute' => 'clases',
                    'format' => 'text',
                    'value' => $searchModel->clases_descripcion,
                ],
                [
                    'class' =>  yii\grid\DataColumn::className(), // this line is optional
                    'attribute' => 'proveedor',
                    'format' => 'text',
                    'value' => $searchModel->proveedor_descripcion,
                ],
                
            ],
        ]);
        echo $filtro_consulta; ?>
        <?php Pjax::begin(); ?>    

        <?php 
          if ($searchModel->agrupado=='A'){
            $columnas_agrupar = [
                [
                    'attribute' => 'AR_CODART',
                    'group' => true,
                    'groupedRow'=>true,     
                    'value'=>function ($model, $key, $index, $widget) { 
                        return "Artículo: ".$model['AR_CODART'].' - '.$model['AG_NOMBRE'];
                    },
                    'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                    'groupEvenCssClass'=>'kv-grouped-row',
                    'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                        return [
                            //'mergeColumns'=>[[2,4]], // columns to merge in summary
                            'content'=>[             // content to show in each summary cell
                                1=>'Total '.$model['AR_CODART'].' - '.$model['AG_NOMBRE'],
                                3=>GridView::F_SUM,
                                5=>GridView::F_SUM,
                            ],5=>GridView::F_SUM,
                            'contentFormats'=>[      // content reformatting for each summary cell
                                3=>['format'=>'number', 'decimals'=>2],
                                5=>['format'=>'number', 'decimals'=>2],
                            ],
                            'contentOptions'=>[      // content html attributes for each summary cell
                                1=>['style'=>'font-variant:small-caps'],
                                3=>['style'=>'text-align:right'],
                                5=>['style'=>'text-align:right'],
                            ],
                           'options'=>['style'=>'font-weight:bold;text-align:right;']
                        ];
                    }
                ],
                [
                    'attribute' => 'PR_RAZONSOC',
                    'label'=> "Proveedor",
                    'value'=>function ($model, $key, $index, $widget) { 
                        if (isset($model['PR_CODIGO']))
                           return $model['PR_CODIGO'].' - '.$model['PR_RAZONSOC'];
                       else
                           return 'Sin proveedor';
                    },
                    'pageSummary' => 'Total',
                    'pageSummaryOptions'=>['class'=>'text-right text-warning'],
                ],
                 
            ];
             $titulo= "Ingresos por Artículo";
          }else{
            $columnas_agrupar = [
                [
                    'attribute' => 'PR_RAZONSOC',
                    'label'=> "Proveedor",
                     'group' => true,
                    'groupedRow'=>true,     
                    'value'=>function ($model, $key, $index, $widget) { 
                        if (isset($model['PR_CODIGO']))
                           return $model['PR_CODIGO'].' - '.$model['PR_RAZONSOC'];
                        else
                           return 'Sin proveedor';
                    },
                    'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                    'groupEvenCssClass'=>'kv-grouped-row',
                    'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                        if (isset($model['PR_CODIGO']))
                           $proveedor = 'Total '.$model['PR_CODIGO'].' - '.$model['PR_RAZONSOC'];
                        else
                           $proveedor =  'Total '.'Sin proveedor';

                        return [
                            //'mergeColumns'=>[[2,4]], // columns to merge in summary
                            'content'=>[             // content to show in each summary cell
                                1=>$proveedor,
                                3=>GridView::F_SUM,
                                5=>GridView::F_SUM,
                            ],
                            'contentFormats'=>[      // content reformatting for each summary cell
                                3=>['format'=>'number', 'decimals'=>2],
                                5=>['format'=>'number', 'decimals'=>2],
                            ],
                            'contentOptions'=>[      // content html attributes for each summary cell
                                1=>['style'=>'font-variant:small-caps'],
                                3=>['style'=>'text-align:right'],
                                5=>['style'=>'text-align:right'],
                            ],
                           'options'=>['style'=>'font-weight:bold;text-align:right;']
                        ];
                    }
                ],
                [
                    'attribute' => 'AR_CODART',
                    'label' => 'Artículo',
                    'value'=>function ($model, $key, $index, $widget) { 
                        return "Artículo: ".$model['AR_CODART'].' - '.$model['AG_NOMBRE'];
                    },
                    'pageSummary' => 'Total',
                    'pageSummaryOptions'=>['class'=>'text-right text-warning'],
                ],
                
                 
            ];
            $titulo= "Ingresos por Proveedor";
          }
          $columnas_restantes = [
                [
                    'attribute'=>'RA_FECHA', 
                    'format'=>['date', 'php:d-m-Y'], 
                    'width'=>'100px',
                    'hAlign'=>'center',
                    'label'=> "Fecha", 
                ],
                [
                    'attribute'=>'AR_CANTID', 
                    'format'=>['decimal', 2], 
                    'hAlign'=>'right', 
                    'width'=>'100px', 
                    'label' => "Cantidad",
                    'pageSummary'=>true,
                ],
                 [
                    'attribute'=>'AR_PRECIO', 
                   'format'=>['decimal', 2], 
                    'hAlign'=>'right', 
                    'width'=>'100px', 
                    'label' => "Valor Unitario",
                    
                ],
                 [
                    'class'=>'kartik\grid\FormulaColumn',
                    'header'=>'Valor',
                    'value'=>function ($model, $key, $index, $widget) { 
                        $p = compact('model', 'key', 'index');
                        return $widget->col(3, $p) * $widget->col(4, $p);
                    },
                    'mergeHeader'=>true,
                    'width'=>'150px',
                    'hAlign'=>'right',
                    'format'=>['decimal', 2],
                    'pageSummary'=>true,
                ],];
          $columnas = array_merge($columnas_agrupar,$columnas_restantes);

          $pdfHeader = [
            'L' => [
                'content' => '',
                 'font-size' => 15,
                'color' => '#377333'
            ],
            'C' => [
                'content' => " <div><img src='images/header_label.png' alt=''></div><div>$titulo</div>",
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
                'content' => 'Depósito Central '.date("d-m-Y h:i"),
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

            echo GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'showPageSummary' => true,
            'pjax'=>true,
            // 'striped'=>true,
            // 'hover'=>true,            
            'panel'=>['type'=>'primary', 'heading'=>$titulo],
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
                    'filename' => 'ingresos',
                ],
                GridView::PDF => [
                       
                        'filename' => 'ingresos',
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
                            'contentBefore' => $filtro_consulta,
                        ]
                    ],
                ],
            'columns' => $columnas,
        ]); 
    
    ?>
    <?php Pjax::end(); }?></div>

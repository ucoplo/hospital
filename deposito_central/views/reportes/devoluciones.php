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
use deposito_central\models\ArticGral;
use yii\helpers\Url;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel deposito_central\models\Movimientos_diariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Devoluciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devoluciones-index"><?php
    if ($filtro){?>

 <h3><?= Html::encode($this->title) ?></h3>

 
    <div class="devoluciones-search">

            <?php $form = ActiveForm::begin([
                'action' => ['devoluciones'],
                'method' => 'post',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-2',
                        'wrapper' => 'col-sm-10',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
            ]); ?>

           <?= $form->field($searchModel, 'deposito')->dropDownList($searchModel->listaDepositos, ['disabled'=>!$filtro,'prompt' => '']);?>

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
                    <label class="control-label col-sm-2">Fecha</label>
            
                <div class="col-md-10 ">
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
                'initValueText' => $articuloNombre, // set the initial display text
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

            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($searchModel, 'planillas')->checkbox(array(
                                
                                'labelOptions'=>array('style'=>'padding:5px;'),
                                                          
                                ))
                                ->label('Planillas de Sala'); ?>
                </div>
                
                <div class="col-md-3">
                    <?= $form->field($searchModel, 'sobrante')->checkbox()
                                ->label('Sobrante de Salas'); ?>
                </div>

            </div>
            <div class="row">
                <div class="col-md-2 col-md-offset-10 text-right"><?php
                if($filtro) {
                    echo Html::submitButton('Buscar...', ['class' => 'btn btn-primary']);
                }?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

    </div>

<?php }
   if (!$filtro){ 
        
        $pdfHeader = [
            'L' => [
                'content' => '',
                 'font-size' => 15,
                'color' => '#377333'
            ],
            'C' => [
                'content' => " <div><img src='images/header_label.png' alt=''></div><div>Devoluciones</div>",
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
        ?>
        <div class="form-group">
            <?= Html::a('Nueva Búsqueda',['devoluciones'], ['class' => 'btn btn-success']);?>
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
                    'attribute' => 'tipo',
                    'format' => 'text',
                    'value' => $searchModel->tipo_devolucion_descripcion,
                ],
       
            ],
        ]);
        echo $filtro_consulta; ?>
<?php Pjax::begin(); ?>    <?php


echo GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax'=>true,
        'panel'=>['type'=>'primary', 'heading'=>'Devoluciones'],
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
                'filename' => 'devoluciones',
            ],
            GridView::PDF => [
                   
                    'filename' => 'devoluciones',
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
        'columns' => [
             [
                'attribute'=>'codigo', 
                'width'=>'50px',
            ],
             [
                'attribute'=>'articulo', 
                'width'=>'350px',
            ],
            [
                'attribute'=>'cantidad', 
                'format'=>['decimal', 2], 
                'hAlign'=>'right',
                'width'=>'100px', 
               
            ],
            [
                'attribute'=>'valor', 
                'format'=>['decimal', 2], 
                'hAlign'=>'right', 
                'width'=>'100px', 
            ],
            
           
        ],
    ]); ?>
<?php Pjax::end(); }?></div>




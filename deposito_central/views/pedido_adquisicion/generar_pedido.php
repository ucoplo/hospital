<?php

use yii\helpers\Html;

use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use unclead\widgets\MultipleInput;
use unclead\widgets\MultipleInputColumn;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use yii\helpers\ArrayHelper;
use deposito_central\models\ArticGral;
use yii\web\JsExpression;
use deposito_central\models\Clases;
use deposito_central\assets\Pedido_adquisicionAsset;

Pedido_adquisicionAsset::register($this);

/* @var $this yii\web\View */
/* @var $model deposito_central\models\Pedido_adquisicion */

$this->title = 'Nuevo Pedido de Adquisicion';
$this->params['breadcrumbs'][] = ['label' => 'Pedidos de Adquisición', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedido-adquisicion-create">

    <h2><?= Html::encode($this->title) ?></h2>

   
    <div class="pedido-adquisicion-form">

    <?php 
   
        $lista_clases = [''=>'']+ArrayHelper::map(
            Clases::find()->all(),'CL_COD',
            function($model, $defaultValue) {return "[{$model->CL_COD}] {$model->CL_NOM}";}
        );

    $form = ActiveForm::begin([
            'action' => ['generar_pedido'],
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-md-9',
                    'wrapper' => 'col-md-3'
                ]
            ],
            'layout' => 'horizontal'
        ]); ?>
        

    
     <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'PE_DEPOSITO', ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])->dropDownList($model->listaDeposito, ['prompt' => 'Seleccione Depósito','readonly'=>true ]);?>
        </div>
        <div class="col-md-8">
          <?= $form->field($model, 'PE_CLASES', ['horizontalCssClasses' => ['label' => 'col-md-2', 'wrapper' => 'col-md-10']])->widget(Select2::classname(), [
                'data' => $model->listaClases,
                'options' => ['placeholder' => '','multiple' => true,'readonly'=>true],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
        </div>
    </div>
    

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'PE_ARTDES', ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])->textInput(['maxlength' => true,'readonly'=>true]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'PE_ARTHAS', ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])->textInput(['maxlength' => true,'readonly'=>true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'PE_TIPO')->widget(CheckboxX::classname(), [
                                 'autoLabel' => false,'readonly'=>true, 'pluginOptions'=>['threeState'=>false]]); ?>
        </div>
    </div>

   <div class="row">
        
        <div class="col-md-4">
            <?= $form->field($model, 'PE_CLASABC')->widget(CheckboxX::classname(), [
                                 'autoLabel' => false,'readonly'=>true, 'pluginOptions'=>['threeState'=>false]]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'PE_DIASABC')->textInput(['maxlength' => true,'readonly'=>true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'PE_EXISACT')->widget(CheckboxX::classname(), ['readonly'=>true,'pluginOptions'=>['threeState'=>false]]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'PE_PEDPEND')->widget(CheckboxX::classname(), ['readonly'=>true,'pluginOptions'=>['threeState'=>false]]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'PE_PONDHIS')->textInput(['maxlength' => true,'readonly'=>true]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'PE_PONDPUN')->textInput(['maxlength' => true,'readonly'=>true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'PE_DIASPREVIS')->textInput(['maxlength' => true,'readonly'=>true]) ?>   
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'PE_DIASDEMORA')->textInput(['maxlength' => true,'readonly'=>true]) ?>   
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'PE_REFERENCIA', ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])->textarea(['rows' => 3,'readonly'=>true]) ?>
        </div>
    </div>            

    <hr />

    <div class="row">
        <div class="col-md-4">
        <?= $form->field($model, 'PE_NUM', 
            ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->textInput(['readonly' => 'true','maxlength' => true,'value' => str_pad($model->PE_NUM, 6, '0', STR_PAD_LEFT)]) ?>
        </div>
        <div class="col-md-3">
             <?= $form->field($model, 'PE_FECHA', ['horizontalCssClasses' => ['label' => 'col-md-6', 'wrapper' => 'col-md-6']])
            ->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'autoWidget' => false,
                'options' => ['readonly' => 'true'],
            ]);?>
        
        </div>
        <div class="col-md-5">
        	<?= $form->field($model, 'PE_COSTO')->textInput(['maxlength' => true,'readonly'=>true]) ?>
       </div>
    </div>
    <?php echo $form->errorSummary($model); ?>
    <?php $deposito = $model->PE_DEPOSITO;
   ?>
    <?= $form->field($model, 'renglones', ['horizontalCssClasses' => ['label' => 'col-md-0', 'wrapper' => 'col-md-12 col-sm-offset-0']])->widget(MultipleInput::className(), [
            //'min' => 1,
            'allowEmptyList'    => true,
            'addButtonPosition' => MultipleInput::POS_HEADER,
            'enableGuessTitle'  => true,
         
            'columns' => [
                [
                    'name'  => 'PE_CODART',
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'headerOptions' => [
                        'class' => 'text-center col-md-1',
                    ],
                ],
                [
                    'name'  => 'PE_CLASE',
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'headerOptions' => [
                        'class' => 'text-center col-md-1',
                    ],
                ],
               [
                    'name'  => 'descripcion',
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'headerOptions' => [
                        'class' => 'text-center col-md-1',
                    ],
                    'value' => function($data) {
                        return $data['descripcion'];
                    },   
                ],
               [
                    'name'  => 'BUSCAR_ARTICULO',
                    'enableError' => true,
                    'title' => 'Artículo',
                    'type' => Select2::classname(),
                   
                    'headerOptions' => [
                        'class' => 'text-center col-md-4',
                    ],
                    'options' => function($data) use ($model){
                        $deposito = $model->PE_DEPOSITO;
                        $url_busqueda_articulos = \yii\helpers\Url::to(['pedido_adquisicion/buscar-articulos','deposito'=>$deposito]);
                        return 
                        [
                            'data' => (!empty($data['PE_CODART']))?[ "{$data['PE_CODART']}" => "[{$data['PE_CODART']}] ".$data['descripcion']]:[],

                            'pluginOptions' => [
                                'ajax' => [
                                    'url' => $url_busqueda_articulos,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) {
                                      return {q:params.term};
                                    }')
                                ],
                                'disabled' => true,
                                'enableEmpty' => true,
                                'minimumInputLength' => 1,
                                'language' => 'es',
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(articulo) { return articulo.text; }'),
                                'templateSelection' => new JsExpression('function (articulo) { return articulo.text; }'),
                            ],
                            'pluginEvents' => [

                                "select2:select" => "function(result) {
                                    if (!codigo_unico($(this)))
                                    {
                                        $(this).val('').trigger('change');
                                        krajeeDialog.alert('No puede repetirse el articulo');
                                    }
                                    else{
                                        id_articulo = result.params.data.id;

                                        este_select2_id = $(this).attr('id');
                                        hidden_articulo = este_select2_id.replace('buscar_articulo','pe_codart');
                                        $('#'+hidden_articulo).val(id_articulo);

                                        codart= $(this).val();
                                        deposito=$('#pedido_adquisicion-pe_deposito').val();
                                        existencia_actual=$('#pedido_adquisicion-pe_exisact').val();
                                        cant_pend_entrega=$('#pedido_adquisicion-pe_pedpend').val();
                                        cons_historico=$('#pedido_adquisicion-pe_pondhis').val();
                                        cons_puntual=$('#pedido_adquisicion-pe_pondpun').val();
                                        dias_prevision=$('#pedido_adquisicion-pe_diasprevis').val();
                                        dias_tramite=$('#pedido_adquisicion-pe_diasdemora').val();

                                        $.ajax({
                                              url: 'index.php?r=pedido_adquisicion/datos_nuevo_renglon',
                                              dataType: 'json',
                                              method: 'POST',
                                              data: {codart: codart,
                                                    deposito: deposito,
                                                    existencia_actual:existencia_actual,
                                                    cant_pend_entrega:cant_pend_entrega,
                                                    cons_historico:cons_historico,
                                                    cons_puntual:cons_puntual,
                                                    dias_prevision:dias_prevision,
                                                    dias_tramite:dias_tramite
                                                },
                                              success: function (data, textStatus, jqXHR) {
                                                   clase = este_select2_id.replace('buscar_articulo','clase');
                                                   $('#'+clase).val(''+data['PE_CLASE']);
                                          
                                                   pe_clase = este_select2_id.replace('buscar_articulo','pe_clase');
                                                   $('#'+pe_clase).val(data['PE_CLASE']);

                                                   precio = este_select2_id.replace('buscar_articulo','precio');
                                                   $('#'+precio).val(data['precio']);
                                                   descripcion = este_select2_id.replace('buscar_articulo','descripcion');
                                                   $('#'+descripcion).val(data['descripcion']);
                                                   pe_cantped = este_select2_id.replace('buscar_articulo','pe_cantped');
                                                   $('#'+pe_cantped).val(data['PE_CANTPED']);
                                                   cantidad_sugerida = este_select2_id.replace('buscar_articulo','cantidad_sugerida');
                                                   $('#'+cantidad_sugerida).val(data['cantidad_sugerida']);
                                                   cons_puntual = este_select2_id.replace('buscar_articulo','cons_puntual');
                                                   $('#'+cons_puntual).val(data['cons_puntual']);
                                                   cons_historico = este_select2_id.replace('buscar_articulo','cons_historico');
                                                   $('#'+cons_historico).val(data['cons_historico']);
                                                   existencia = este_select2_id.replace('buscar_articulo','existencia');
                                                   $('#'+existencia).val(data['existencia']);
                                                   pendiente_entrega = este_select2_id.replace('buscar_articulo','pendiente_entrega');
                                                   $('#'+pendiente_entrega).val(data['pendiente_entrega']);
                                                   cantidad_pack = este_select2_id.replace('buscar_articulo','cantidad_pack');
                                                   $('#'+cantidad_pack).val(data['cantidad_pack']);
                                              },
                                          }).fail(function(model, response) {
                                            
                                            
                                        });
                                    }
                                }",
                            ]
                        ];
                    },
                ],
            
                [
                    'name'  => 'clase',
                    'enableError' => true,
                    'title' => 'Clase',
                    'type'  => 'dropDownList',
                    'items' => function($data) use ($lista_clases){
                                 return $lista_clases; 
                    },
                    'defaultValue' => '',
                    'value' => function($data) {
                        return (!empty($data['PE_CLASE']))?$data['PE_CLASE']:'';
                    },
                    'headerOptions' => [
                        'class' => 'text-center col-md-4',
                    ],
                    'options' => function ($data) { return
                       ['readonly'=> 'readonly','disabled'=>true];
                    }
                ],          
            
                [
                    'name' => 'precio',
                    
                   
                    'options' => [
                        'readonly' => true,
                     
                    ],
                    'headerOptions' => [
                        'class' => 'text-center col-md-1',
                    ],
                    'value' => function($data) {
                        //return Yii::$app->formatter->asCurrency($data['precio']);
                        return $data['precio'];
                    },          
                    'title' => 'precio',
                ],      
                 [
                    'name'  => 'PE_CANTPED',
                      'enableError' => true,
                    'title' => 'Cant. PEDIDA',
                     'headerOptions' => [
                        'class' => 'text-center col-md-1',
                    ],
                ],
                [
                    'name' => 'cantidad_sugerida',
                    
                    'options' => [
                        'readonly' => true,
                    ],
                    'value' => function($data) {
                        return $data['cantidad_sugerida'];
                    },          
                    'title' => 'Cant. SUGERIDA',
                    'headerOptions' => [
                        'class' => 'text-center col-md-1',
                    ],
                ],     
                [
                    'name' => 'cons_puntual',
                    
                    'options' => [
                        'readonly' => true,
                    ],
                    'value' => function($data) {
                        return $data['cons_puntual'];
                    },  
                     'headerOptions' => [
                        'class' => 'text-center col-md-1',
                    ],        
                    'title' => "Cons.Ult. $model->PE_DIASDEMORA días",
                ],   
                [
                    'name' => 'cons_historico',
                    
                    'options' => [
                        'readonly' => true,
                    ],
                    'value' => function($data) {
                        return $data['cons_historico'];
                    },    
                     'headerOptions' => [
                        'class' => 'text-center col-md-1',
                    ],      
                    'title' => 'Cons. Prom. His.',
                ],  
                [
                    'name' => 'existencia',
                    
                    'options' => [
                        'readonly' => true,
                    ],
                    'value' => function($data) {
                        return $data['existencia'];
                    },         
                     'headerOptions' => [
                        'class' => 'text-center col-md-1',
                    ], 
                    'title' => 'Existen.',
                ],    
                 [
                    'name' => 'pendiente_entrega',
                    
                    'options' => [
                        'readonly' => true,
                    ],
                    'value' => function($data) {
                        return $data['pendiente_entrega'];
                    },          
                    'headerOptions' => [
                        'class' => 'text-center col-md-1',
                    ],
                    'title' => 'Pend. Ent.',
                ],    
                 [
                    'name' => 'cantidad_pack',
                    
                    'options' => [
                        'readonly' => true,
                    ],
                     'headerOptions' => [
                        'class' => 'text-center col-md-1',
                    ],
                    'value' => function($data) {
                        return $data['cantidad_pack'];
                    },          
                    'title' => 'Cant. x Pack',
                ],    
                
                
            ]
         ])->label(false);
        ?>
    <div class="form-group">
        <?= Html::submitButton('Guardar Pedido', ['class'=>'btn btn-success']) ?>
        
    </div>
    <?= $form->field($model, 'PE_HORA')->hiddenInput()->label(false);?>

    <?php ActiveForm::end(); ?>

</div>

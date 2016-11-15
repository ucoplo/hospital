<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use deposito_central\models\Deposito;
use deposito_central\models\Pedido_adquisicion;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel farmacia\models\Remito_depositoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orden de Compra';
$this->params['breadcrumbs'][] = ['label' => 'Remitos de Adquisición', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="remito-deposito-index">

   
    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
    <?php echo $this->render('_search_orden_compra', ['model' => $searchModel]); ?>


<?php Pjax::begin(); ?>    
    <?php if (isset($orden)){ 
        if (!empty($orden)){?>   
    <?= DetailView::widget([
            'model' => $orden,
            'attributes' => [
                'numero',
                'ejercicio',
                'proveedor.PR_RAZONSOC',
                'OC_FECHA:date',
                'OC_FINALIZADA',
                'OC_PEDADQ',
            ],
    ]); 
    if (isset($orden->OC_PEDADQ)){
        echo Html::a('Crear Adquisición', ['create_orden_compra', 'id' => $orden['OC_NRO']], ['class' => 'btn btn-success']);
    }else{
        echo Html::a('Asociar Pedido', ['create_orden_compra', 'id' => $orden['OC_NRO']], ['class' => 'btn btn-success popupPedidos']);
    }
    ?>
<?php } else{?>
<div class="alert alert-warning"><?= $mensaje_busqueda?></div>
<?php } } Pjax::end(); ?>
  

</div>
<?php
    yii\bootstrap\Modal::begin([
        'header' => '<center><h2>Pedidos Adquisición</h2></center>',
        'id' =>'modal',
        'size' => 'modal-lg',
    ]);
    echo '<div class="row text-center">';
    echo "   <div class='row'>
            <div class='col-md-3'>
            </div>
            <div class='col-md-6'>";
            if (isset($orden) && !empty($orden)){
               $form = ActiveForm::begin(
                    [
                       'id' => 'form-asociar-pedido',
                        'action'=>['asociar_pedido'],
                        'method'=>'post',
                        'options' => [
                            'data-pjax' => true,
                        ],
                        'enableAjaxValidation' => true,
                        'validationUrl' => Url::to(['remito_adquisicion/validate-asociar-pedido']),
                    ]
                );

                echo $form->field($searchModel, 'OC_PEDADQ')->widget(Select2::classname(), [
                    'data' =>  ArrayHelper::map($searchModel->pedidos_pendientes(), 'PE_NUM', 
                                        function($model, $defaultValue) {
                                            return $model['PE_NUM'].'-'.Yii::$app->formatter->asDate($model['PE_FECHA'],'php:d-m-Y');
                                        }),
                    'options' => ['placeholder' => 'Seleccione Pedido ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label("Pedido");
                $searchModel->OC_NRO = $orden['OC_NRO'];
                echo $form->field($searchModel, 'OC_NRO')->hiddenInput()->label(false);
                
                echo Html::submitButton('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Asociar', ['class' => 'btn btn-default btn-sm','id'=>'submit_foja_susp']);
                ActiveForm::end();
            }   
                echo "</div></div></div>"  ;   
    yii\bootstrap\Modal::end();

    $this->registerJs("$(function() {
       $('.popupPedidos').click(function(e) {
         e.preventDefault();
         $('#modal').modal('show');
       });
    });");

?>

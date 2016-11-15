<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\dialog\Dialog;
use yii\jui\Spinner;


echo Dialog::widget();

$this->title = 'Importar RAFAM';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alarma-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <div class="col-md-2">
        </div>
        <div class="col-md-8">
	        <?= Html::a('Importar Proveedores', ['importar_proveedores'], ['id'=>'btn_importar_proveedores','class' => 'btn btn-success btn-lg btn-block']) ?>
	        <?= Html::a('Importar Ordenes de Compra', ['importar_ordenes'], ['id'=>'btn_importar_ordenes','class' => 'btn btn-success btn-lg btn-block']) ?>
	    </div>    
		<div class="col-md-2">
        </div>
       
   </div>

  
<?php
            Modal::begin([
                'header' => 'Importación txt Kairos',
                'id' => 'modal_importar_txt',
                'size' => 'modal-sm',
                'closeButton'=>false,
                'clientOptions' => ['backdrop' => 'static','keyboard'=> false],
                //'toggleButton' => ['label' => 'Mas datos','class' => 'btn btn-primary'],
            ]);
            echo "<div id='wrp_mensaje_modal' class='text-center'>"
            		 .Html::img('images/spin.gif').
            	  "</div>";
            Modal::end();
        ?>
</div>

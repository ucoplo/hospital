<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model farmacia\models\Ambulatorios_ventanilla */

$this->title = 'Nuevo Vale Ambulatorios Ventanilla';
$this->params['breadcrumbs'][] = ['label' => 'Vales Ambulatorios Ventanilla', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ambulatorios-ventanilla-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    Modal::begin([
	        'id' => 'modal_form_ventanilla',
	        'size' => 'modal-lg',
	        //keeps from closing modal with esc key or by clicking out of the modal.
	        // user must click cancel or X to close
	        'clientOptions' => ['backdrop' => 'static'],
	        
	    ]);
	    echo "<div id='modalContent'>Por favor espere mientras se cargan los datos...</div>";
	    Modal::end();
	?>
    <?= $this->render('_form', [
        'model' => $model,
        'paciente' => $paciente,
    ]) ?>

</div>




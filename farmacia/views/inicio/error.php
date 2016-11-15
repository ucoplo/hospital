<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

//$this->title = $name;
$this->title ='Acceso Denegado';
?>
<div class="site-error">

    <h2><?= Html::encode('Error Inesperado') ?></h2>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    

</div>

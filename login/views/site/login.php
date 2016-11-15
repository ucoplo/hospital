<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'] = null;
?>
<div class="site-login container">

     <div class="text-center"><?= Html::img('images/logo-2015.png');?></div>
    <h3 class="text-center">Ingreso al Sistema de Gestión Hospitalario</h3>

   
    <div class="row">
        <div class="col-md-3 centrar" >
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'LE_NUMLEGA')->textInput(['autofocus' => true, ]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <!--<?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                </div>-->

                <div class="form-group text-center">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary ', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

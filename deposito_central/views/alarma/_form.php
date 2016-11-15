<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model deposito_central\models\Alarma */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alarma-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'AL_DEPOSITO')->dropDownList($model->listaDepositos, ['prompt' => 'Seleccione Depósito']);?>

    



	<?php  // Get the initial city description
    $url = \yii\helpers\Url::to(['query']);
     
    echo $form->field($model, 'AL_CODMON')->widget(Select2::classname(), [
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
             'templateResult' => new JsExpression('function(techo) { return techo.text; }'),
             'templateSelection' => new JsExpression('function (techo) { return techo.text; }'),
        ],
    ]);
    ?>

    <?= $form->field($model, 'AL_MIN')->textInput() ?>

    <?= $form->field($model, 'AL_MAX')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Nueva' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Volver',['index'],array('class'=>'btn btn-primary'));?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

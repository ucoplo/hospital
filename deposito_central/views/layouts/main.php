<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use deposito_central\assets\AppAsset;
use common\widgets\Alert;
use kartik\nav\NavX;
use kartik\dialog\Dialog;

//Dialogos genericos para mostrar procesos exitosos o con error
echo Dialog::widget();
echo Dialog::widget([
        'libName' => 'DialogExito',
        'options' => [
            'type' => Dialog::TYPE_SUCCESS,
            'title' => 'Información'],
    ]);
echo Dialog::widget([
        'libName' => 'DialogError',
        'options' => [
            'type' => Dialog::TYPE_DANGER,
            'title' => 'Error'],
    ]);

if (Yii::$app->session->hasFlash('exito_deposito_central')) {
    $mensaje = 'DialogExito.alert("'.Yii::$app->session->getFlash('exito_deposito_central').'");';
    $this->registerJs($mensaje);
}

if (Yii::$app->session->hasFlash('error_deposito_central')) {
    $mensaje = 'DialogError.alert("'.Yii::$app->session->getFlash('error_deposito_central').'");';
    $this->registerJs($mensaje);
}



AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/images/favicon.ico" type="image/x-icon" /> 
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
   
<div class="wrap">
    <?php 
    if (Yii::$app->user->isGuest) {
        Yii::$app->user->setReturnUrl($_SERVER["REQUEST_URI"]);
        $user_item = ['label' => 'Login', 'url' => Yii::$app->user->loginUrl];
        
        //$user_item = ['label' => 'Login', 'url' => [Url::toRoute('site/login')]];
    } else {
        $user_item = 
            //Html::beginForm('../../login/web/index.php?r=site/logout', 'post')
            Html::beginForm(Yii::$app->params['logout_url'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->LE_APENOM . ')',
                ['class' => 'btn btn-link menu-login','style'=>'float:right;color:white;']
            )
            . Html::endForm()
            ;
    }
    
    // NavBar::begin([
    //     'options' => [
    //         'class' => 'navbar navbar-fixed-top',
    //     ],
    // ]);
    echo NavX::widget([
        'options' => ['class' => 'nav nav-pills'],
        'items' => [
            ['label' => 'DEPOSITO', 'url' => ['/inicio/principal']],
            ['label' => 'Adquisiciones', 'items' => [
                ['label' => 'Pedidos de Reposición (Interacción con RAFAM)', 'url' => ['/pedido_adquisicion']],
                ['label' => 'Registrar entradas', 'url' => ['/remito_adquisicion']],
                ['label' => 'Registrar devoluciones a Proveedores', 'url' => ['/devolucion_proveedor']],
                ['label' => 'Importar Datos RAFAM', 'url' => ['/importar_rafam']],
            ]],
            ['label' => 'Suministros', 'items' => [
                 ['label' => 'Servicios Planilla de Retiro a granel', 'url' => ['/planilla_entrega']],
                 ['label' => 'Devoluciones', 'items' => [
                            ['label' => 'Planilla de sala', 'url' => ['/devolucion_salas']],
                            ['label' => 'Sobrantes de Sala', 'url' => ['/devolucion_salas_sobrante']],
                ]],
                ['label' => 'Pérdidas', 'url' => ['/perdidas']],
            ]],

            
            ['label' => 'Control de Stock', 'items' => [
                ['label' => 'Movimientos Diarios','url' => ['/movimientos_diarios/seleccionar_movimientos'] ],
                ['label' => 'Blanqueo Stock Lotes','url' => ['/movimientos_diarios/blanquear_stock'] ],
            ]],
            ['label' => 'Consultas y Reportes', 'items' => [
                ['label' => 'Ingresos', 'url' => ['reportes/ingresos']],
                ['label' => 'Cardex de artículos', 'url' => ['reportes/cardex_articulos'] ],
                ['label' => 'Cardex por lote de Vencimiento', 'url' => ['reportes/cardex_lotes'] ],
                ['label' => 'Stock (existencias)', 'url' => ['reportes/stock']],
                ['label' => 'Reposición',  'items' => [
                    ['label' => 'Pedidos pendientes', 'url' => ['reportes/pedidos_pendientes']],
                    ['label' => 'Previsión', 'url' => ['reportes/prevision']],
                    ['label' => 'Salida Valorizada', 'url' => ['reportes/salida_valorizada']],
                ]],
                 ['label' => 'Análisis Rotacional',  'items' => [
                    ['label' => 'ABC', 'url' =>  ['reportes/abc']],
                    ['label' => 'ABC por servicios', 'url' => ['reportes/abc_servicio']],
                    ['label' => 'Última salida', 'url' => ['reportes/ultima_salida']],
                    ['label' => 'Índices', 'url' => ['reportes/indices']],
                    ['label' => 'Salida media', 'url' => ['reportes/salida_media']],
                ]],
                ['label' => 'Consumos',  'items' => [
                    ['label' => 'Alarmas', 'url' => ['reportes/alarmas']],
                    ['label' => 'Consumo por artículo', 'url' => ['reportes/consumo_por_articulo']],
                    ['label' => 'Consumo por Servicio y Artículo', 'url' => ['reportes/consumo_por_servicio']],
                    ['label' => 'Consumo por Servicio y Clase', 'url' => ['reportes/consumo_por_servicio_clase']],
                ]],
                ['label' => 'Pérdidas', 'url' => ['reportes/perdidas']],
                ['label' => 'Devoluciones', 'url' => ['reportes/devoluciones']],
                ['label' => 'Vencimientos',  'items' => [
                    ['label' => 'Por artículo', 'url' => ['reportes/articulos_vencimientos']],
                    ['label' => 'Artículos Vencidos', 'url' => ['reportes/articulos_vencidos']],
                    ['label' => 'Artículos a vencer', 'url' => ['reportes/articulos_por_vencer']],
                ]],
            ]],
            ['label' => 'Configuración',  'items' => [
                ['label' => 'Usuarios', 'url' => '#'],
                ['label' => 'Catálogos',  'items' => [
                    ['label' => 'Artículos', 'url' => ['/articgral']],
                    ['label' => 'Proveedores', 'url' => ['/proveedores']],
                    ['label' => 'Clases', 'url' => ['/clases']],
                    ['label' => 'Depósitos', 'url' => ['/deposito']],
                    ['label' => 'Servicios', 'url' => ['/servicio']],
                    ['label' => 'Alarmas', 'url' => ['/alarma']],
                    ['label' => 'Techos de pedidos', 'url' => ['/techo_articulo']],
                    ['label' => 'Motivo de pérdidas', 'url' => ['/motivo_perdida']],
                ]],
                
            ]],
         $user_item   
        ],
        'encodeLabels' => false
    ]);
    // NavBar::end();
        ?>
    <?php
    /*NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();*/
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>


<footer class="footer">
    <div class="rayita"></div>
    <div class="div_footer">
        <img src="<?php echo Yii::$app->request->baseUrl; ?>/images/logo-hospital-byn.png" witdh="" height="35px">
        <span>Dirección: Estomba 968, Bahía Blanca, Argentina / Teléfono: (0291) 459-8484
        Hospital Municipal de Agudos Dr. Leónidas Lucero / <?php echo date("Y"); ?> Todos los derechos reservados
        </span>
        <img src="<?php echo Yii::$app->request->baseUrl; ?>/images/logo-bahia-byn.png" witdh="" height="32px">
    </div>
</footer>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

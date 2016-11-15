<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use farmacia\assets\AppAsset;
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

if (Yii::$app->session->hasFlash('exito_farmacia')) {
    $mensaje = 'DialogExito.alert("'.Yii::$app->session->getFlash('exito_farmacia').'");';
    $this->registerJs($mensaje);
}

if (Yii::$app->session->hasFlash('error_farmacia')) {
    $mensaje = 'DialogError.alert("'.Yii::$app->session->getFlash('error_farmacia').'");';
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
             ['label' => 'FARMACIA'],
            ['label' => 'Adquisiciones', 'items' => [
                ['label' => 'Registrar entradas por adquisiciones', 'url' => ['/remito_adquisicion']],
                ['label' => 'Registrar Devoluciones', 'url' => ['/devolucion_proveedor']],
            ]],
            ['label' => 'Suministros', 'items' => [
                 ['label' => 'Ambulatorios', 'items' => [
                            ['label' => 'A partir de vales de enfermería para ambulatorios', 'url' => ['/consumo_medicamentos_pacientes/index','condpac'=> 'A']],
                            ['label' => 'Ventanillas', 'url' => ['/ambulatorios_ventanilla']],
                ]],
                 ['label' => 'Internados', 'items' => [
                            ['label' => 'A partir de vales de enfermería para internados', 'url' => ['/consumo_medicamentos_pacientes/index','condpac'=> 'I'] ],
                ]],
                 ['label' => 'Servicios', 'items' => [
                            ['label' => 'Planilla de retiro a granel', 'url' =>  ['/consumo_medicamentos_granel']],
                ]],
                 ['label' => 'Devoluciones', 'items' => [
                            ['label' => 'Planilla de sala (Internados)', 'url' => ['/devolucion_salas_granel']],
                            ['label' => 'Vales de enfermería', 'url' =>  ['/devolucion_salas_paciente']],
                            ['label' => 'Sobrantes de Sala', 'url' => ['/devolucion_salas_sobrante']],
                ]],
            ]],
            ['label' => 'Pedidos', 'items' => [
                ['label' => 'Pedidos de reposición a Dep. Central', 'url' => ['/pedidos_reposicion']],
            ]],
            ['label' => 'Control de Stock', 'items' => [
                ['label' => 'Movimientos Diarios','url' => ['/movimientos_diarios/seleccionar_movimientos'] ],
                ['label' => 'Blanqueo Stock Lotes','url' => ['/movimientos_diarios/blanquear_stock'] ],
                ['label' => 'Pérdidas','url' => ['/perdidas'] ],
            ]],
            ['label' => 'Consultas y Reportes', 'items' => [
                ['label' => 'Ingresos', 'items' => [
                     ['label' => 'Por Monodroga', 'url' => ['reportes/ingreso_monodroga']],
                ]],
                ['label' => 'Vademecuns', 'items' => [
                    ['label' => 'Por Medicamento comercial', 'url' => ['reportes/medicamentos']],
                    ['label' => 'Por Monodrogas (genéricos)', 'url' => ['reportes/monodrogas']],
                ]],
                ['label' => 'Cardex de artículos', 'url' => ['reportes/cardex_articulos'] ],
                ['label' => 'Cardex por lote de Vencimiento', 'url' => ['reportes/cardex_lotes'] ],
                ['label' => 'Stock (existencia)', 'url' => ['reportes/stock']],
                ['label' => 'Reposición',  'items' => [
                    ['label' => 'Previsión', 'url' => ['reportes/reposicion_prevision']],
                ]],
                 ['label' => 'Análisis Rotacional',  'items' => [
                    ['label' => 'ABC', 'url' =>  ['reportes/abc']],
                    ['label' => 'ABC por servicios', 'url' => ['reportes/abc_servicio']],
                    ['label' => 'Última salida', 'url' => ['reportes/ultima_salida']],
                    ['label' => 'Índices', 'url' => ['reportes/indices']],
                    ['label' => 'Salida media', 'url' => ['reportes/salida_media']],
                ]],
                ['label' => 'Consumos',  'items' => [
                    ['label' => 'Alarmas',  'items' => [
                        ['label' => 'Alarmas Estáticas', 'url' => ['reportes/alarmas_estaticas']],
                        ['label' => 'Alarmas Dinámicas', 'url' => ['reportes/alarmas_dinamicas']],
                    ]],
                    
                    ['label' => 'Consumo por Monodroga', 'url' => ['reportes/consumo_por_monodroga']],
                    ['label' => 'Consumo por Servicio y Monodroga', 'url' => ['reportes/consumo_por_servicio']],
                    ['label' => 'Consumo por Servicio y clase', 'url' => ['reportes/consumo_por_servicio_clase']],
                    ['label' => 'Consumo por Vales o planilla de sala con demanda insatisfecha', 'url' => ['reportes/consumo_por_vales_planillas']],
                    ['label' => 'Stock de monodrogas fraccioandas en sala', 'url' => ['reportes/stock_monodrogas_sala']],
                    ['label' => 'Techos', 'url' => ['reportes/techos']],
                    ['label' => 'Consumos por paciente',  'items' => [
                        ['label' => 'Por U.D.', 'url' => ['reportes/consumos_paciente_por_ud']],
                        ['label' => 'Por Pacientes', 'url' => ['reportes/consumos_paciente']],
                        ['label' => 'Por Paciente servicio con cantidad pedida', 'url' => ['reportes/consumos_paciente_servicio_cantped']],
                        ['label' => 'Por Pacientes servicio médico U.D. y acción terapéutica', 'url' => ['reportes/consumos_paciente_servicio_ud_at']],
                        ['label' => 'Por Pacientes Ambulatorios y Servicios', 'url' => ['reportes/consumos_paciente_ambu_servicio']],
                        ['label' => 'Por Pacientes Ambulatorios de SMU', 'url' => ['reportes/consumos_paciente_ambu_smu']],
                        
                        
                    ]],
                ]],
                ['label' => 'Pérdidas', 'url' => ['reportes/perdidas']],
                ['label' => 'Devoluciones', 'url' => ['reportes/devoluciones']],
                ['label' => 'Vencimientos',  'items' => [
                    ['label' => 'Por monodrogas', 'url' => ['reportes/monodrogas_vencimientos']],
                    ['label' => 'Monodrogas Vencidas', 'url' => ['reportes/monodrogas_vencidas']],
                    ['label' => 'Monodrogas a vencer', 'url' => ['reportes/monodrogas_por_vencer']],
                ]],
                ['label' => 'Ventanillas',  'items' => [
                    ['label' => 'Con demanda insatisfecha', 'url' => ['reportes/consumos_ventanilla_demanda']],
                    ['label' => 'Consumo por unidad sanitaria y pacientes', 'url' => ['reportes/consumos_ventanilla_unidad']],
                    ['label' => 'Consumos por Pacientes Ambulatorios discriminando por OOSS', 'url' => ['reportes/consumos_paciente_ambu_ooss']],
                    ['label' => 'Consumo por Pacientes Ambulatorios valorizado', 'url' => ['reportes/consumos_paciente_ambu_valorizado']],
                    ['label' => 'Libro de Psicofármacos', 'url' => ['reportes/libro_psicofarmacos']],
                ]],
                ['label' => 'Kairos',  'items' => [
                    ['label' => 'Actualizaciones', 'url' => ['/importar_kairos']],
                    ['label' => 'Productos', 'url' => ['/productos_kairos']],
                ]],
            ]],
            ['label' => 'Configuración',  'items' => [
                ['label' => 'Usuarios', 'url' => '#'],
                ['label' => 'Catálogos',  'items' => [
                    ['label' => 'Medicamentos con Marca Comercial', 'url' => ['/medic']],
                    ['label' => 'Monodrogas o genéricos', 'url' => ['/articgral']],
                    ['label' => 'Laboratorios', 'url' => ['/labo']],
                    ['label' => 'Clases', 'url' => ['/clases']],
                    ['label' => 'Acciones Terapéuticas', 'url' => ['/accionterapeutica']],
                    ['label' => 'Techos', 'url' => ['/techo']],
                    ['label' => 'Depósitos', 'url' => ['/deposito']],
                    ['label' => 'Servicios', 'url' => ['/servicio']],
                    ['label' => 'Alarmas', 'url' => ['/alarma']],
                    ['label' => 'Motivos de pérdida', 'url' => ['/motivo_perdida']],
                    ['label' => 'Vías', 'url' => ['/via']],
                    ['label' => 'Drogas', 'url' => ['/droga']],
                ]],
                
            ]],

         $user_item,


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

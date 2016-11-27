<?php

namespace deposito_central\controllers;

use Yii;
use deposito_central\models\Planilla_entrega;
use deposito_central\models\Planilla_entregaSearch;
use deposito_central\models\PedidoInsumosSearch;
use deposito_central\models\PedidosReposicionFarmaciaSearch;
use deposito_central\models\PedidosReposicionFarmacia;
use deposito_central\models\PedidosReposicionFarmacia_renglones;
use deposito_central\models\PedidoInsumos;
use deposito_central\models\Planilla_entrega_renglones;
use deposito_central\models\Vencimientos;
use deposito_central\models\ArticGral;
use deposito_central\models\Movimientos_diarios;
use deposito_central\models\Movimientos_sala;
use deposito_central\models\Movimientos_quirofano;
use deposito_central\models\Remito_deposito;
use deposito_central\models\Remito_deposito_renglones;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\ErrorException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;


/**
 * Planilla_entregaController implements the CRUD actions for Planilla_entrega model.
 */
class Planilla_entregaController extends Controller
{
   public $CodController="013";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::classname(),
                
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=> ['@'],
                        'actions'=>['index','create','view','seleccion_servicio',
                               'create_sin_pedido','iniciar_creacion','report'],
                        'matchCallback' => 
                            function ($rule, $action) {
                                return Yii::$app->user->identity->habilitado($action);
                            }
                    ],
                     [
                        'allow'=>true,
                        'roles'=> ['@'],
                        'actions' => ['vencimiento_vigente_codart',
                                      'procesar',
                                      'iniciar_creacion_farmacia',
                                      'seleccion_pedido_farmacia'],
                        
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Planilla_entrega models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Planilla_entregaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     public function actionSeleccion_servicio()
    {
        $searchModel = new PedidoInsumosSearch();
        
        $dataProvider = $searchModel->vales_servicio_listado();

        return $this->render('seleccion_servicio', [
            'dataProvider' => $dataProvider,
        ]);

        
    }

     public function actionSeleccion_pedido_farmacia()
    {
        $search_pedidos_farmacia = new PedidosReposicionFarmaciaSearch();

        $dataProvider = $search_pedidos_farmacia->pedidos_listado();

        return $this->render('seleccion_pedido_farmacia', [
            'dataProvider' => $dataProvider,
        ]);

        
    }

    /**
     * Displays a single Planilla_entrega model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionIniciar_creacion($vale)
    {
      try {
         $model = new Planilla_entrega();

         $pedido_insumos = PedidoInsumos::findOne($vale);

         $model->PE_SERSOL = $pedido_insumos->VD_SERSOL;
         $model->PE_DEPOSITO = $pedido_insumos->VD_DEPOSITO;
         $model->PE_FECHA = date('Y-m-d');
         $model->PE_HORA = date('H:i:s');
         $model->PE_CODOPE = Yii::$app->user->identity->LE_NUMLEGA; //El usuario logueado
         $model->PE_ENFERM = $pedido_insumos->VD_SUPERV;
         $model->PE_NUMVALE = $vale;

         $renglones = [];
        $pedido_entregado_parcialmente = false;
        foreach ($pedido_insumos->renglones as $key_reng_pedido => $renglon_pedido) {

          $cantidad_entregada = 0;
          foreach ($pedido_insumos->planillas_entrega as $key_planilla => $planilla) {
            foreach ($planilla->renglones_planilla as $key_reng_planilla => $renglon_planilla) {
              if ($renglon_planilla->PR_CODART==$renglon_pedido->VD_CODMON){
                $cantidad_entregada += $renglon_planilla->PR_CANTID;
                $pedido_entregado_parcialmente = true;
              }
            }
          }
          if ($cantidad_entregada<$renglon_pedido->VD_CANTID){
            //Verifico que el articulo ingresado en el pedido exista en el Depósito
            if (!isset($renglon_pedido->articulo)){
              throw new ErrorException("El artículo $renglon_pedido->VD_CODMON del pedido de Insumo seleccionado no existe en el Deposito $renglon_pedido->VD_DEPOSITO");
            }
            $nuevo_renglon = new Planilla_entrega_renglones();           
            $nuevo_renglon->PR_CODART = $renglon_pedido->VD_CODMON;
            $nuevo_renglon->PR_CANTID = $renglon_pedido->VD_CANTID-$cantidad_entregada;
            $nuevo_renglon->descripcion = $renglon_pedido->articulo->AG_NOMBRE;
            $fecha = $this->vencimiento_vigente($nuevo_renglon->PR_CODART,$renglon_pedido->VD_DEPOSITO);
            if ($fecha)
                $nuevo_renglon->PR_FECVTO = Yii::$app->formatter->asDate($fecha->DT_FECVEN,'php:d-m-Y');
            
            $renglones[] = $nuevo_renglon;
          }
        }
        if ($pedido_entregado_parcialmente)
          Yii::$app->getSession()->setFlash('exito_deposito_central', 'El Pedido seleccionado a sido parcialmente entregado.');

          // foreach ($pedido_insumos->renglones as $key => $renglon) {
          //       $nuevo_renglon = new Planilla_entrega_renglones();           
          //       $nuevo_renglon->PR_CODART = $renglon->VD_CODMON;
          //       $nuevo_renglon->PR_CANTID = $renglon->VD_CANTID;
          //       $nuevo_renglon->descripcion = $renglon->articulo->AG_NOMBRE;
          //       $fecha = $this->vencimiento_vigente($nuevo_renglon->PR_CODART,$renglon->VD_DEPOSITO);
          //       if ($fecha)
          //           $nuevo_renglon->PR_FECVTO = Yii::$app->formatter->asDate($fecha->DT_FECVEN,'php:d-m-Y');
                
          //       $renglones[] = $nuevo_renglon;
          //     }


        $model->renglones = $renglones;

         return $this->render('create', [
                'model' => $model,
            ]);
       }
       catch (\Exception $e) {
                   
                    
                    Yii::$app->getSession()->setFlash('error_deposito_central', $e->getMessage());

                    return $this->redirect(['seleccion_servicio']);
                }
    }

     public function actionIniciar_creacion_farmacia($pedido)
    {
      try {
         $model = new Planilla_entrega();

         $pedido_insumos = PedidosReposicionFarmacia::findOne($pedido);

         $model->PE_SERSOL = $pedido_insumos->PE_SERSOL;
         $model->PE_DEPOSITO = $pedido_insumos->PE_DEPOSITO;
         $model->PE_FECHA = date('Y-m-d');
         $model->PE_HORA = date('H:i:s');
         $model->PE_CODOPE = Yii::$app->user->identity->LE_NUMLEGA; //El usuario logueado
         $model->PE_ENFERM = $pedido_insumos->PE_SUPERV;
         $model->PE_NUMVALE = $pedido;

         $renglones = [];
        $pedido_entregado_parcialmente = false;
        foreach ($pedido_insumos->renglones as $key_reng_pedido => $renglon_pedido) {

          $cantidad_entregada = 0;
          foreach ($pedido_insumos->planillas_entrega as $key_planilla => $planilla) {
            foreach ($planilla->renglones_planilla as $key_reng_planilla => $renglon_planilla) {
              if ($renglon_planilla->PR_CODART==$renglon_pedido->PE_CODMON){
                $cantidad_entregada += $renglon_planilla->PR_CANTID;
                $pedido_entregado_parcialmente = true;
              }
            }
          }
          if ($cantidad_entregada<$renglon_pedido->PE_CANTPED){
            //Verifico que el articulo ingresado en el pedido exista en el Depósito
            if (!isset($renglon_pedido->articulo)){
              throw new ErrorException("El artículo $renglon_pedido->PE_CODMON del pedido de Farmacia seleccionado no existe en el Deposito $renglon_pedido->PE_DEPOSITO");
            }
            $nuevo_renglon = new Planilla_entrega_renglones();           
            $nuevo_renglon->PR_CODART = $renglon_pedido->PE_CODMON;
            $nuevo_renglon->PR_CANTID = $renglon_pedido->PE_CANTPED-$cantidad_entregada;
            $nuevo_renglon->descripcion = $renglon_pedido->articulo->AG_NOMBRE;
            $fecha = $this->vencimiento_vigente($nuevo_renglon->PR_CODART,$renglon_pedido->PE_DEPOSITO);
            if ($fecha)
                $nuevo_renglon->PR_FECVTO = Yii::$app->formatter->asDate($fecha->DT_FECVEN,'php:d-m-Y');
            
            $renglones[] = $nuevo_renglon;
          }
        }
        if ($pedido_entregado_parcialmente)
          Yii::$app->getSession()->setFlash('exito_deposito_central', 'El Pedido seleccionado a sido parcialmente entregado.');

        $model->renglones = $renglones;

         return $this->render('create', [
                'model' => $model,
            ]);
       }
       catch (\Exception $e) {
                   
                    
                    Yii::$app->getSession()->setFlash('error_deposito_central', $e->getMessage());

                    return $this->redirect(['seleccion_pedido_farmacia']);
                }
    }
    //Ultima entrega de medicamento que se efectuo
    private function ultima_salida($codart,$deposito){

      $query1 = (new \yii\db\Query())
          ->select("max(PE_fecha) as fecha")
          ->from('valefar')
          ->join('INNER JOIN', 'consmed', 'consmed.PE_nroval = valefar.va_nrovale')
          ->where(['va_codart' => $codart,'VA_DEPOSITO' => $deposito]);

      $query2 = (new \yii\db\Query())
          ->select("max(am_fecha) as fecha")
          ->from('ambu_ren')
          ->join('INNER JOIN', 'ambu_enc', 'ambu_enc.am_numvale = ambu_enc.am_numvale')
          ->where(['am_codart' => $codart,'ambu_enc.AM_DEPOSITO' => $deposito]);


      $unionQuery = (new \yii\db\Query())
          ->select("max(fecha) as ult_fecha")
          ->from(['salidas' => $query1->union($query2)]);
         
       
       return $unionQuery->one()['ult_fecha'];

    }

     private function deshacer_renglones($model)
    {
       $renglones = Planilla_entrega_renglones::find()->where(['PR_NROREM' => $model->PE_NROREM])->all();
              
       foreach ($renglones as $key => $renglon) {


        $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->PR_CODART,
                                                      'AG_DEPOSITO' => $renglon->PR_DEPOSITO,
                                                      
                                                      ])->one();

        if ($artic_gral){

            $artic_gral->AG_STACDEP += $renglon->PR_CANTID;
                      
            $artic_gral->AG_ULTSAL = $this->ultima_salida($renglon->PR_CODART,$renglon->PR_DEPOSITO);

            if (!$artic_gral->save(false)){
              $mensaje = ""; 
              foreach ($artic_gral->getFirstErrors() as $key => $value) {
                $mensaje .= "\\n\\r $value";
              }
              
              throw new ErrorException($mensaje);
            }
        }   
            //Incrementamos la cantidad en Vencimientos
            $vencimiento = Vencimientos::find()->where(['DT_CODART' => $renglon->PR_CODART,
                                                        'DT_FECVEN' => $renglon->PR_FECVTO,
                                                        'DT_DEPOSITO' => $renglon->PR_DEPOSITO,
                                                        ])->one();

        if (count($vencimiento)==1){
            $vencimiento->DT_SALDO += $renglon->PR_CANTID;
            if (!$vencimiento->save()){
              $mensaje = ""; 
              foreach ($vencimiento->getFirstErrors() as $key => $value) {
                $mensaje .= "\\n\\r $value";
              }
              
              throw new ErrorException($mensaje);
            }
            

        }

        //Registramos el Movimiento Diario
        $movimiento = Movimientos_diarios::find()->where(['DM_CODART' => $renglon->PR_CODART,
                                                      'DM_FECVTO' => $renglon->PR_FECVTO,
                                                      'DM_DEPOSITO' => $renglon->PR_DEPOSITO,
                                                      'DM_CODMOV' => "V",
                                                      'DM_FECHA' => $model->PE_FECHA,
                                                      ])->one();
                                     
          if ($movimiento){
              $movimiento->DM_CANT -= $renglon->PR_CANTID;
              if ($movimiento->DM_CANT <=0){
                $movimiento->delete();
              }
              else{
                if (!$movimiento->save()){
                  $mensaje = ""; 
                  foreach ($movimiento->getFirstErrors() as $key => $value) {
                    $mensaje .= "\\n\\r $value";
                  }
                  
                  throw new ErrorException($mensaje);
                }
              }
          }
          $renglon->delete();
       }

    }

    private function guardar_renglones($model)
    {
      try{
        //Si el servicio solicitante el Farmacia se le genera un Remito para ser importado
        if ($model->PE_SERSOL==Yii::$app->params['servicio_farmacia']){
          $remito = new Remito_deposito();
          $remito->RS_CODEP = $model->PE_DEPOSITO;
          $remito->RS_FECHA = $model->PE_FECHA;
          $remito->RS_HORA = $model->PE_HORA;
          $remito->RS_CODOPE = $model->PE_CODOPE;
          $remito->RS_NUMPED = $model->PE_NROREM;
          $remito->RS_SERSOL = $model->PE_SERSOL;
          $remito->RS_IMPORT = 'F';
          if (!$remito->save()){
            $mensaje = ""; 
            foreach ($remito->getFirstErrors() as $key => $value) {
              $mensaje .= "$value \\n\\r";
            }
            throw new ErrorException($mensaje);
          }
        }

        $num_renglon = 1;
        foreach ($model->renglones as $key => $obj) {
            
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $obj['PR_CODART'],
                                                  'AG_DEPOSITO' => $model->PE_DEPOSITO,
                                                 ])->one();

          //Solo se actualiza stock si el medicamento No se fracciona en Sala
          if ($artic_gral){
              $artic_gral->AG_STACDEP -= $obj['PR_CANTID'];
              $artic_gral->AG_ULTSAL = date('Y-m-d');
              if (!$artic_gral->save(false)){
                $mensaje = ""; 
                foreach ($artic_gral->getFirstErrors() as $key => $value) {
                  $mensaje .= "\\n\\r $value";
                }
                
                throw new ErrorException($mensaje);
              }
              
          
            //Decrementamos la cantidad en Vencimientos
            $cantidad_entregada = $obj['PR_CANTID'];
            
            while ( $cantidad_entregada > 0) {
                $vencimiento_vigente = $this->vencimiento_vigente($obj['PR_CODART'],$model->PE_DEPOSITO);

                if ($vencimiento_vigente){
                  if ($vencimiento_vigente->DT_SALDO < $cantidad_entregada){
                    $cantidad_entregada -= $vencimiento_vigente->DT_SALDO;
                    $cantidad_renglon = $vencimiento_vigente->DT_SALDO;
                    $vencimiento_vigente->DT_SALDO = 0;
                  }else{
                    $vencimiento_vigente->DT_SALDO -= $cantidad_entregada;
                    $cantidad_renglon = $cantidad_entregada;
                    $cantidad_entregada =0;
                  }
                  
                  if (!$vencimiento_vigente->save()){
                    $mensaje = ""; 
                    foreach ($vencimiento_vigente->getFirstErrors() as $key => $value) {
                      $mensaje .= "\\n\\r $value";
                    }
                    
                    throw new ErrorException($mensaje);
                  }
                  //Se guarda cada renglón del Vale
                  $renglon = new Planilla_entrega_renglones();

                  $renglon->PR_NROREM = $model->PE_NROREM;
                  $renglon->PR_DEPOSITO = $model->PE_DEPOSITO;
                  $renglon->PR_CODART = $obj['PR_CODART'];
                  
                  $renglon->PR_FECVTO =  $vencimiento_vigente->DT_FECVEN;
                  $renglon->PR_CANTID = $cantidad_renglon;
                 

                  if (!$renglon->save()){
                    $mensaje = ""; 
                    foreach ($renglon->getFirstErrors() as $key => $value) {
                      $mensaje .= "\\n\\r $value";
                    }
                    
                    throw new ErrorException($mensaje);
                  }

                  //Se guarda cada renglón del Remito para Farmacia si corresponde
                  if ($model->PE_SERSOL==Yii::$app->params['servicio_farmacia']){
                    $renglon_remito = new Remito_deposito_renglones();

                    $renglon_remito->RS_NROREM = $remito->RS_NROREM;
                    $renglon_remito->RS_CODEP = $model->PE_DEPOSITO;
                    $renglon_remito->RS_CODMON = $obj['PR_CODART'];
                    $renglon_remito->RS_NUMRENG = $num_renglon;
                    $renglon_remito->RS_FECVTO =  $vencimiento_vigente->DT_FECVEN;
                    $renglon_remito->RS_CANTID = $cantidad_renglon;
                    $renglon_remito->RS_VALULTCOMP = $artic_gral->AG_PRECIO;
                   
                    if (!$renglon_remito->save()){
                      $mensaje = ""; 
                      foreach ($renglon_remito->getFirstErrors() as $key => $value) {
                        $mensaje .= "\\n\\r $value";
                      }
                      throw new ErrorException($mensaje);
                    }
                  }
                  //Registramos el Movimiento Diario
                  $movimiento = Movimientos_diarios::find()->where(['DM_CODART' => $renglon->PR_CODART,
                                                              'DM_FECVTO' => $renglon->PR_FECVTO,
                                                              'DM_DEPOSITO' => $renglon->PR_DEPOSITO,
                                                              'DM_CODMOV' => "V",
                                                              'DM_FECHA' => $model->PE_FECHA,
                                                              ])->one();
                                             
               
                  if ($movimiento){
                      $movimiento->DM_CANT += $renglon->PR_CANTID;
                  }
                  else{

                      $movimiento = new Movimientos_diarios();
                      $movimiento->DM_FECHA = $model->PE_FECHA;
                      $movimiento->DM_CODMOV = "V";
                      $movimiento->DM_CANT = $renglon->PR_CANTID;
                      $movimiento->DM_FECVTO =  $renglon->PR_FECVTO;
                      $movimiento->DM_CODART = $renglon->PR_CODART;
                      $movimiento->DM_DEPOSITO = $renglon->PR_DEPOSITO;

                  }

                  if (!$movimiento->save()){
                    $mensaje = ""; 
                    foreach ($movimiento->getFirstErrors() as $key => $value) {
                      $mensaje .= "\\n\\r $value";
                    }
                    
                    throw new ErrorException($mensaje);
                  }

                  $num_renglon++;   
                                        
                }
            }
            
            $servicios_quirofano = Yii::$app->params['servicios_quirofano'];

            //Si se fracciona en Quirofana la cantidad se multiplica por las unidades del envase
            if ($artic_gral->AG_FRACCQ=='S'){
              $cantidad_entrada = $obj['PR_CANTID']*$artic_gral->AG_UNIENV;
            }else{
              $cantidad_entrada = $obj['PR_CANTID'];
            }
           
            if (in_array($model->PE_SERSOL,$servicios_quirofano )){

              $movimiento_quirofano = Movimientos_quirofano::find()->where(['MO_CODART' => $obj['PR_CODART'],
                                                          'MO_DEPOSITO' =>$model->PE_DEPOSITO,
                                                          'MO_TIPMOV' => "F",
                                                          'MO_FECHA' => $model->PE_FECHA,
                                                          'MO_IDFOJA' => null,
                                                          'MO_SECTOR' => $model->PE_SERSOL
                                                          ])->one();
                                         
           
              if ($movimiento_quirofano){
                  $movimiento_quirofano->MO_CANTIDA += $cantidad_entrada;
              }
              else{

                  $movimiento_quirofano = new Movimientos_quirofano();
                  $movimiento_quirofano->MO_FECHA = $model->PE_FECHA;
                  $movimiento_quirofano->MO_HORA = $model->PE_HORA;
                  $movimiento_quirofano->MO_SECTOR = $model->PE_SERSOL;
                  $movimiento_quirofano->MO_TIPMOV = "F";
                  $movimiento_quirofano->MO_CANTIDA = $cantidad_entrada;
                  $movimiento_quirofano->MO_CODART = $obj['PR_CODART'];
                  $movimiento_quirofano->MO_DEPOSITO = $model->PE_DEPOSITO;
                 
              }
              
              if (!$movimiento_quirofano->save()){
                $mensaje = ""; 
                foreach ($movimiento_quirofano->getFirstErrors() as $key => $value) {
                  $mensaje .= "\\n\\r $value";
                }
                
                throw new ErrorException($mensaje);
              }
            }
            else{
              $movimiento_sala = Movimientos_sala::find()->where(['MO_CODMON' => $obj['PR_CODART'],
                                                          'MO_DEPOSITO' =>$model->PE_DEPOSITO,
                                                          'MO_TIPMOV' => "O",
                                                          'MO_FECHA' => $model->PE_FECHA,
                                                          'MO_CODSERV' => $model->PE_SERSOL,
                                                          'MO_HISCLI' => null,
                                                          ])->one();
                                         
           
              if ($movimiento_sala){
                  $movimiento_sala->MO_CANT += $obj['PR_CANTID'];
              }
              else{

                  $movimiento_sala = new Movimientos_sala();
                  $movimiento_sala->MO_FECHA = $model->PE_FECHA;
                  $movimiento_sala->MO_HORA = $model->PE_HORA;
                  $movimiento_sala->MO_TIPMOV = "O";
                  $movimiento_sala->MO_CANT = $obj['PR_CANTID'];
                  $movimiento_sala->MO_CODMON = $obj['PR_CODART'];
                  $movimiento_sala->MO_DEPOSITO = $model->PE_DEPOSITO;
                  $movimiento_sala->MO_CODSERV =  $model->PE_SERSOL;
                  $movimiento_sala->MO_SUPOPE =  Yii::$app->user->identity->LE_NUMLEGA; //El usuario logueado;
                  

              }
              
              if (!$movimiento_sala->save()){
                $mensaje = ""; 
                foreach ($movimiento_sala->getFirstErrors() as $key => $value) {
                  $mensaje .= "\\n\\r $value";
                }
                
                throw new ErrorException($mensaje);
              }
            }
          }
        }
      }
      catch (\Exception $e) {
          throw $e;
      }
    }

    private function verificar_pedido_completo($pedido_insumo){
    try {
        $PedidoInsumos = PedidoInsumos::findOne($pedido_insumo);

        $renglones_incompletos = array();
        foreach ($PedidoInsumos->renglones as $key_reng_pedido => $renglon_pedido) {
          $cantidad_entregada = 0;
          foreach ($PedidoInsumos->planillas_entrega as $key_planilla => $planilla) {
            foreach ($planilla->renglones_planilla as $key_reng_planilla => $renglon_planilla) {
              if ($renglon_planilla->PR_CODART==$renglon_pedido->VD_CODMON){
                $cantidad_entregada += $renglon_planilla->PR_CANTID;
              }
            }
          }
          if ($cantidad_entregada<$renglon_pedido->VD_CANTID){
            $renglones_incompletos[]=$renglon_pedido->VD_CODMON;
          }
        }

        if (count($renglones_incompletos)==0){
          $PedidoInsumos->VD_PROCESADO = 1;
          if (!$PedidoInsumos->save()){
            $mensaje = ""; 
            foreach ($PedidoInsumos->getFirstErrors() as $key => $value) {
              $mensaje .= "$value \\n\\r";
            }
            throw new ErrorException($mensaje);
          }
        }
      } catch (Exception $e) {
       throw $e;
      }
    }
     /**
     * Creates a new Planilla_entrega model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Planilla_entrega();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {
                        
            if ($model->validate() ){   
                
                
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                    //Se guarda encabezado de la PLanilla
                     $model->PE_PROCESADO = 0;
                     if ($model->save()){
                        $this->guardar_renglones($model);
                                                
                      }else{
                         $mensaje = ""; 
                        foreach ($model->getFirstErrors() as $key => $value) {
                          $mensaje .= "$value \\n\\r";
                        }
                        
                        throw new ErrorException($mensaje);
                      }

                      $this->verificar_pedido_completo($model->PE_NUMVALE);

                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('exito_deposito_central', 'Planilla de entrega creada con éxito.');
                    return $this->redirect(['view', 'id' => $model->PE_NROREM]);
                    
                }
                catch (\Exception $e) {
                    $transaction->rollBack();
                    
                    Yii::$app->getSession()->setFlash('error_deposito_central', $e->getMessage());

                    return $this->render('create', [
                    'model' => $model,
                    ]);
                }
            } else {
                return $this->render('create', [
                'model' => $model,
                ]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreate_sin_pedido()
    {
        $model = new Planilla_entrega();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {
                        
            if ($model->validate() ){   
                
                
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                    //Se guarda encabezado de la PLanilla
                     $model->PE_PROCESADO = 0;
                     $model->PE_NUMVALE = null;
                     if ($model->save()){
                        $this->guardar_renglones($model);
                                                
                      }else{
                         $mensaje = ""; 
                        foreach ($model->getFirstErrors() as $key => $value) {
                          $mensaje .= "$value \\n\\r";
                        }
                        
                        throw new ErrorException($mensaje);
                      }

                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('exito_deposito_central', 'Planilla de entrega creada con éxito.');
                    return $this->redirect(['view', 'id' => $model->PE_NROREM]);
                    
                }
                catch (\Exception $e) {
                    $transaction->rollBack();
                    
                    Yii::$app->getSession()->setFlash('error_deposito_central', $e->getMessage());

                    return $this->render('create_sin_pedido', [
                    'model' => $model,
                    ]);
                }
            } else {
                return $this->render('create_sin_pedido', [
                'model' => $model,
                ]);
            }
            
        } else {
         
           $model->PE_FECHA = date('Y-m-d');
           $model->PE_HORA = date('H:i:s');
           $model->PE_CODOPE = Yii::$app->user->identity->LE_NUMLEGA; //El usuario logueado
         
           $model->PE_NUMVALE = null;

           $model->renglones = [];


            return $this->render('create_sin_pedido', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Updates an existing Planilla_entrega model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post())) {
             if ($model->validate() ){            
          
                  $connection = \Yii::$app->db;
                  $transaction = $connection->beginTransaction();

                  try {
                      //Se guarda encabezado Vale deposito_central
                      if ($model->save()){
                        $this->deshacer_renglones($model);
                        $this->guardar_renglones($model);
                      }
                      $transaction->commit();
                     return $this->redirect(['view', 'id' => $model->PE_NROREM]);
                  }
                  catch (\Exception $e) {
                      $transaction->rollBack();
                      throw $e;
                  }
              }
              else{
                 return $this->render('update', [
                    'model' => $model,
                     
                ]);
              }
            
        } else {
            

              $renglones = $this->agrupar_medicamentos($model->renglones);
              
              foreach ($renglones as $key => $renglon) {
                $renglones[$key]->descripcion = $renglon->articulo->AG_NOMBRE;
                $renglon->PR_FECVTO = Yii::$app->formatter->asDate($renglon->PR_FECVTO,'php:d-m-Y');
              }

              $model->renglones = $renglones;

              
              return $this->render('update', [
                  'model' => $model,
                   
              ]);
           
        }
    }

    /**
     * Deletes an existing Planilla_entrega model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {   
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        try {
          $model = $this->findModel($id);
          
          $this->deshacer_renglones($model);

          $model->delete();
          
          $transaction->commit();
          
          return $this->redirect(['index']);

        }
        catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

    }

    /**
     * Finds the Planilla_entrega model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Planilla_entrega the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Planilla_entrega::findOne($id)) !== null) {

            $searchModel = new Planilla_entrega_renglones();
            $model->renglones =  $searchModel->get_renglones($id);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function vencimiento_vigente($codart,$deposito){

       $query =  Vencimientos::find()->where(['DT_CODART' => $codart,
                                                     'DT_DEPOSITO' => $deposito,
                                                   
                                                      ]);

       
       $query->andWhere([">", 'DT_SALDO', 0]);

       $query->orderBy(['DT_FECVEN'=>SORT_ASC]);


       $vencimientos = $query->one(); 

       
       return $vencimientos;

    }

    private function agrupar_medicamentos($renglones){
      
      $codigos_medicamentos=[];
      $codart = '';
      $cant_renglones = count($renglones);
      $nuevos_renglones = [];

      $sum_cant_ent = 0;
      $sum_cant_ped = 0;
      $renglones = $renglones->getModels();
      for ($i=0; $i < $cant_renglones; $i++) { 
      
        $renglon = $renglones[$i];



        if ($renglon->PR_CODART!=$codart){
          
          if ($codart != ''){

            $renglon_nuevo->PR_CANTID = number_format($sum_cant_ent, 2, '.', '');
            $nuevos_renglones[] = $renglon_nuevo;

            $renglon_nuevo = $renglon;
            $codart = $renglon_nuevo->PR_CODART;
            $sum_cant_ent = $renglon->PR_CANTID;
            
            
          }else{
            $renglon_nuevo = $renglon;
            $codart = $renglon_nuevo->PR_CODART;
            $sum_cant_ent += $renglon->PR_CANTID;
            
          }
          if (($i+1)==$cant_renglones){
              $nuevos_renglones[] = $renglon_nuevo;              
            }
        }else{
          
           $sum_cant_ent += $renglon->PR_CANTID;
           

           if (($i+1)==$cant_renglones){
              $renglon_nuevo->PR_CANTID = number_format($sum_cant_ent, 2, '.', '');
              $nuevos_renglones[] = $renglon_nuevo;            
           }
          
        }

      }

      return $nuevos_renglones;
    }

    public function actionReport($id) {

      header('Content-Type: application/pdf');

      $model = $this->findModel($id);
  
      $content = $this->renderPartial('impresion', ['model' => $model]);

      $pdf = new Pdf([
          'mode' => Pdf::MODE_UTF8,
          'format' => Pdf::FORMAT_A4, 
          'orientation' => Pdf::ORIENT_PORTRAIT, 
          'destination' => Pdf::DEST_BROWSER, 
          'content' => $content,//"<html><BODY><h1>prueba</h1></body></html>",  
          'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
          'cssInline' => '.kv-heading-1{font-size:18px}', 
          'options' => ['title' => 'Planilla de entrega'],
      ]);

       return $pdf->render(); 
      
       // $pdf = new Pdf();
       //  $mpdf = $pdf->api; // fetches mpdf api
       //  $mpdf->SetHeader('Kartik Header'); // call methods or set any properties
       //  $mpdf->WriteHtml($content); // call mpdf write html
       //  $mpdf->SetJS('console.log("aaaa");');
        
       //  echo $mpdf->Output('filename', Pdf::DEST_BROWSER); // call the mpdf api output as needed

    }

    public function actionProcesar($id) {

      $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        try {
              $model = $this->findModel($id);
              $model->PE_PROCESADO = 1;
              if (!$model->save()){
                $mensaje = ""; 
                foreach ($model->getFirstErrors() as $key => $value) {
                  $mensaje .= "\\n\\r $value";
                }
                
                throw new ErrorException($mensaje);
              }
              

              $this->generarPdf($id);  
              $transaction->commit();
          
          return \yii\helpers\Json::encode( $model->errors );

        }
        catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->getSession()->setFlash('error_deposito_central', $e->getMessage());
                    
            return \yii\helpers\Json::encode( $e->getMessage());
        }
      
    }

     private function generarPdf($id){
      header('Content-Type: application/pdf');

      $model = $this->findModel($id);
  
      $content = $this->renderPartial('impresion', ['model' => $model]);
      
      $nombre = $id."_". date('d-m-Y')."_".date('Hi').".pdf";

      $nombre = Yii::$app->params['local_path']['path_deposito_central']."/planilla_entrega/".$nombre;

      $pdf = new Pdf([
          'mode' => Pdf::MODE_UTF8,
          'format' => Pdf::FORMAT_A4, 
          'orientation' => Pdf::ORIENT_PORTRAIT, 
          'filename' => $nombre,
          'destination' => Pdf::DEST_FILE, 
          'content' => $content,
          'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
          'cssInline' => '.kv-heading-1{font-size:18px}', 
          'options' => ['title' => 'Planilla de entrega'],
      ]);
      
      $pdf->render();

    }

   
     /*
    Obtiene la fecha de vencimiento mas antigua de un medicamento
    */
    public function actionVencimiento_vigente_codart()
    {

      $codart = $_POST['codart'];
      $deposito = $_POST['deposito'];

       $vencimiento = $this->vencimiento_vigente($codart,$deposito);

       if ($vencimiento){
         $fecha_vto= Yii::$app->formatter->asDate($vencimiento->DT_FECVEN,'php:d-m-Y');
         $vencimiento_result['fecha'] = $fecha_vto;
         $vencimiento_result['saldo'] = $vencimiento->DT_SALDO;         
       }
       else{
         $fecha_vto='';
         $vencimiento_result['fecha'] = '';
         $vencimiento_result['saldo'] = '';         
       }

       

       return \yii\helpers\Json::encode($vencimiento_result);
        
    }

}

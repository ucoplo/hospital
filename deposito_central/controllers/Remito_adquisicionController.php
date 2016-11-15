<?php

namespace deposito_central\controllers;

use Yii;
use deposito_central\models\Remito_adquisicion;
use deposito_central\models\Remito_adquisicion_renglones;
use deposito_central\models\Remito_adquisicionSearch;
use deposito_central\models\OrdenCompraSearch;
use deposito_central\models\OrdenCompra;
use deposito_central\models\OrdenCompra_renglones;
use deposito_central\models\Pedido_adquisicion;
use deposito_central\models\Proveedores;

use deposito_central\models\Vencimientos;
use deposito_central\models\Movimientos_diarios;
use deposito_central\models\ArticGral;

use kartik\mpdf\Pdf;
use chrmorandi\jasper\Jasper;

use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\ErrorException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider ;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
/**
 * Remito_AdquisicionController implements the CRUD actions for Remito_Adquisicion model.
 */
class Remito_adquisicionController extends Controller
{ 

    public $CodController="011";
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
                            'actions' => ['index','view','seleccion_orden_compra','report',
                                          'asociar_pedido','create_orden_compra','create_adquisicion'],
                            'matchCallback' => 
                                function ($rule, $action) {
                                    return Yii::$app->user->identity->habilitado($action);
                                }
                        ],
                         [
                            'allow'=>true,
                            'roles'=> ['@'],
                            'actions' => ['validate-asociar-pedido',],
                            
                        ]
                    ]
                ]
            ];
        }
    

    /**
     * Lists all Remito_Adquisicion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Remito_adquisicionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Remito_Adquisicion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new Remito_adquisicion_renglones();
        $renglones =  $searchModel->get_renglones($id);
       
        return $this->render('view', [
            'model' => $this->findModel($id),
            'renglones' => $renglones,
        ]);
    }

    private function verificar_proveedor($oc_proveed){
      $proveedor = Proveedores::findOne($oc_proveed);
      //Si no existe el proveedor lo importo
      if (!isset($proveedor)){
        $connection_rafam = \Yii::$app->dbRafam;
        //Importo los proveedores
        $sql= "SELECT * from proveedores where COD_PROV=$oc_proveed";

        $command = $connection_rafam->createCommand($sql);
        $row = $command->queryOne();
        
        $proveedor = new Proveedores();
        $proveedor->PR_CODIGO = $row['COD_PROV'];
        $proveedor->PR_RAZONSOC = $row['RAZON_SOCIAL'];
        $proveedor->PR_TITULAR = $row['FANTASIA'];
        $proveedor->PR_CODRAFAM = $row['COD_PROV'];
        $proveedor->PR_CUIT = $row['CUIT'];
        $proveedor->PR_DOMIC = $row['CALLE_POSTAL'].' '.$row['NRO_POSTAL'].' '.$row['PISO_POSTAL'].' '.$row['DEPT_POSTAL'];
        $proveedor->PR_TELEF = $row['NRO_PAIS_TE1'].' '.$row['NRO_INTE_TE1'].' '.$row['NRO_TELE_TE1'];
        $proveedor->PR_EMAIL = $row['EMAIL'];
        $proveedor->PR_OBS = $row['OBSERVACION'];
        $proveedor->PR_CONTACTO = $row['TE_CELULAR'];
        if (!$proveedor->save()){
            $mensaje = ""; 
            foreach ($proveedor->getFirstErrors() as $key => $value) {
              $mensaje .= "$value \\n\\r";
            }
            
            throw new ErrorException($mensaje);
        }
      }
    }
    //Importa todas las ordenes de compra desde RAFAM
    private function importar_ordenes_rafam($ejercicio,$numero){
      // $stid = oci_parse($conn, $sql);
      //     oci_execute($stid);
  
       //     // echo "<table border='1'>\n";
      //     // while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
      //     //     echo "<tr>\n";
      //     //         foreach ($row as $key=>$item) {
      //     //         echo "    <td>" . ($item !== null ? htmlentities($key.'-'.$item, ENT_QUOTES) : "") . "</td>\n";
      //     //     }
      //     //     echo "</tr>\n";
      //     // }
      //     // echo "</table>\n";
      //   die();
      // $conn = oci_connect('OWNER_RAFAM', 'OWNERDBA', "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST =192.168.0.18)(PORT = 1521)))(CONNECT_DATA=(SID=HMABB)))");
        
      // if (!$conn) {
      //     $e = oci_error();
      //     trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
      // }
      $connection_rafam = \Yii::$app->dbRafam;
      $connection = \Yii::$app->db;
      $transaction = $connection->beginTransaction();

      try {
          

           //Importo los Ordenes de Compra
          $sql= "SELECT oc.NRO_OC,oc.EJERCICIO,TO_CHAR(oc.FECH_OC, 'YYYY-MM-DD') as FECHA_OC,
              oc.COD_PROV, oci.item_oc,oci.descripcion,oci.cantidad,oci.imp_unitario,
              pci.inciso, pci.par_prin, pci.par_parc, pci.clase, pci.tipo
              FROM orden_compra oc, oc_items oci, adjudicaciones a, ped_cotizaciones pc, ped_cotizaciones_items pci
              WHERE oc.ejercicio=oci.ejercicio AND oc.nro_oc=oci.nro_oc AND oc.uni_compra=2 AND oc.estado_oc<>'A' AND oci.uni_compra=2
              AND oc.ejercicio=a.ejercicio AND oc.nro_adjud=a.nro_adjudic AND a.deleg_solic=2 AND a.estado<>'A'
              AND pc.ejercicio=a.ejercicio AND a.nro_coti=pc.nro_coti AND pc.deleg_solic=2 AND pc.estado<>'A' AND pc.nro_llamado=a.nro_llamado
              AND pci.nro_llamado=pc.nro_llamado
              AND pci.ejercicio=pc.ejercicio AND pci.nro_coti=pc.nro_coti AND pci.item_real=oci.item_real 
              AND oc.EJERCICIO=$ejercicio AND oc.NRO_OC=$numero
              ORDER BY oc.EJERCICIO,oc.NRO_OC,oci.item_oc";

          $command = $connection_rafam->createCommand($sql);
          $orden = $command->queryAll();

          $oc_nro_actual='';
          foreach ($orden as $key => $row) {
                    
              $oc_nro = $row['EJERCICIO'].str_pad( $row['NRO_OC'], 6, "0", STR_PAD_LEFT);
              $oc_proveed = $row['COD_PROV'];
              $oc_fecha =   $row['FECHA_OC'];
             
              if ($oc_nro!=$oc_nro_actual){
                  $orden_compra_existente = false;
                  $orden_compra = OrdenCompra::findOne($oc_nro);
                  if (!isset($orden_compra)){
                      $oc_nro_actual = $oc_nro;
                      $orden_compra = new OrdenCompra();
                      $orden_compra->OC_NRO = $oc_nro;
                      $orden_compra->OC_PROVEED = $oc_proveed;
                      $this->verificar_proveedor($oc_proveed);
                      $orden_compra->OC_FECHA =  $oc_fecha;
                      $orden_compra->OC_FINALIZADA = 0; 
                      if (!$orden_compra->save()){
                          $mensaje = ""; 
                          foreach ($orden_compra->getFirstErrors() as $key => $value) {
                            $mensaje .= "$value \\n\\r";
                          }
                          
                          throw new ErrorException($mensaje);
                      }
                      
                  }else{
                      $orden_compra_existente = true;
                  }
              }
              
              if (!$orden_compra_existente){
                  $codigo_rafam_articulo = $row['INCISO'].'.'.$row['PAR_PRIN']
                                  .'.'.$row['PAR_PARC']
                                  .'.'.str_pad( $row['CLASE'], 5, "0", STR_PAD_LEFT)
                                  .'.'.str_pad( $row['TIPO'], 4, "0", STR_PAD_LEFT);

                  $renglon = new OrdenCompra_renglones(); 
                  $renglon->EN_NROOC = $oc_nro;
                  $renglon->EN_ITEM = $row['ITEM_OC'];
                  $renglon->EN_CANT = $row['CANTIDAD'];
                  $renglon->EN_COSTO = $row['IMP_UNITARIO'];
                  $renglon->EN_CODRAFAM = $codigo_rafam_articulo;
                  if (!$renglon->save()){
                      $mensaje = ""; 
                      foreach ($renglon->getFirstErrors() as $key => $value) {
                        $mensaje .= "$value \\n\\r";
                      }
                      
                      throw new ErrorException($mensaje);
                  }
              }
          }

          $transaction->commit();
          return true;
      }
      catch (\Exception $e) {
          $transaction->rollBack();
          throw $e;
      }
    }
    /**
     * Creates a new Remito_Adquisicion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSeleccion_orden_compra()
    {
        $searchModel = new OrdenCompraSearch();
        $searchModel->scenario = "seleccion";
        $searchModel->ejercicio_oc = date ("Y"); 
        $orden = null;
        $mensaje_busqueda = '';
        $params = Yii::$app->request->queryParams; 
       

        $searchModel->load($params);
        if (isset($params[$searchModel->formName()]))
        {

            $numero = $params[$searchModel->formName()]['numero_oc'];
            $numero = str_pad($numero,6,'0',STR_PAD_LEFT);
            $params[$searchModel->formName()]['numero_oc']=$numero;
            $ejercicio = $params[$searchModel->formName()]['ejercicio_oc'];
            $ejercicio = str_pad($ejercicio,4,'0',STR_PAD_LEFT);

            //$params[$searchModel->formName()]['OC_NRO'] = $ejercicio.$numero;
            //print_r($params[$searchModel->formName()]['OC_NRO']);    
            $orden = OrdenCompra::findOne(['OC_NRO'=>$ejercicio.$numero]);
            if (!isset($orden)){
              $this->importar_ordenes_rafam($ejercicio,$numero);
              $orden = OrdenCompra::findOne(['OC_NRO'=>$ejercicio.$numero]);
              if (!isset($orden)){
                $orden = '';
                $mensaje_busqueda = 'La Orden de Compra no existe';
              }
            }else{
              if ($orden->OC_FINALIZADA){
                $orden = '';
                $mensaje_busqueda = 'La Orden de Compra fue recibida totalmente';
              }
            }
        }

        return $this->render('seleccion_orden_compra', [
            'searchModel' => $searchModel,
            'orden'=>$orden,
            'mensaje_busqueda'=>$mensaje_busqueda
        ]);
    }

    private function guardar_renglones($model)
    {

      try {
       $num_renglon = 1;
       foreach ($model->renglones as $key => $obj) {
          $renglon = new Remito_adquisicion_renglones();

           
           $renglon->AR_DEPOSITO = $model->RA_DEPOSITO;
           $renglon->AR_NROREN = $num_renglon;
           $renglon->AR_CODART = $obj['AR_CODART'];
           $renglon->AR_PRECIO = $obj['precio_compra'];
           $renglon->AR_CANTID = $obj['AR_CANTID'];
           $renglon->AR_FECVTO = $obj['AR_FECVTO'];
           $renglon->AR_RENUM = $model->RA_NUM;
          //Se guarda cada renglón del Remito
          
          
          if (!$renglon->save()){
            
            $mensaje = ""; 
            foreach ($renglon->getFirstErrors() as $key => $value) {
              $mensaje .= "\\n\\r $value";
            }
            
            throw new ErrorException($mensaje);
          }

          $num_renglon++;                           
          
          //Incrementamos la cantidad en Vencimientos
          $vencimiento = Vencimientos::find()->where(['DT_CODART' => $renglon->AR_CODART,
                                                      'DT_FECVEN' => $renglon->AR_FECVTO,
                                                      'DT_DEPOSITO' => $renglon->AR_DEPOSITO,
                                                      ])->one();

          if (count($vencimiento)==1){
              $vencimiento->DT_SALDO += $renglon->AR_CANTID;

          }
          else{
              $vencimiento = new Vencimientos();
              $vencimiento->DT_CODART = $renglon->AR_CODART;
              $vencimiento->DT_FECVEN = $renglon->AR_FECVTO;
              $vencimiento->DT_SALDO = $renglon->AR_CANTID;
              $vencimiento->DT_DEPOSITO = $renglon->AR_DEPOSITO;
          }

          
          if (!$vencimiento->save()){
            $mensaje = ""; 
            foreach ($vencimiento->getFirstErrors() as $key => $value) {
              $mensaje .= "\\n\\r $value";
            }
            
            throw new ErrorException($mensaje);
          }

          //Registramos el Movimiento Diario
          $movimiento = Movimientos_diarios::find()->where(['DM_CODART' => $renglon->AR_CODART,
                                                      'DM_FECVTO' => $renglon->AR_FECVTO,
                                                      'DM_DEPOSITO' => $renglon->AR_DEPOSITO,
                                                      'DM_CODMOV' => "C",
                                                      'DM_FECHA' => $model->RA_FECHA,
                                                      ])->one();
                                     
       
          if ($movimiento){
              $movimiento->DM_CANT += $renglon->AR_CANTID;
          }
          else{

              $movimiento = new Movimientos_diarios();
              $movimiento->DM_FECHA = $model->RA_FECHA;
              $movimiento->DM_CODMOV = "C";
              $movimiento->DM_CANT = $renglon->AR_CANTID;
              $movimiento->DM_FECVTO =  $renglon->AR_FECVTO;
              $movimiento->DM_CODART = $renglon->AR_CODART;
              $movimiento->DM_DEPOSITO = $renglon->AR_DEPOSITO;

          }

          if (!$movimiento->save()){
            $mensaje = ""; 
            foreach ($movimiento->getFirstErrors() as $key => $value) {
              $mensaje .= "\\n\\r $value";
            }
            throw new ErrorException($mensaje);
          }

          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->AR_CODART,
                                                      'AG_DEPOSITO' => $renglon->AR_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_PRECIO=$obj['precio_compra'];
              $artic_gral->AG_STACDEP += $renglon->AR_CANTID;

              $artic_gral->AG_ULTENT = date('Y-m-d');

              if (!$artic_gral->save(false)){
                $mensaje = ""; 
                foreach ($artic_gral->getFirstErrors() as $key => $value) {
                  $mensaje .= "\\n\\r $value";
                }
                
                throw new ErrorException($mensaje);
              }
          }
       }
      }
      catch (\Exception $e) {
         throw $e;
      }                   
    }

    public function actionAsociar_pedido()
    {
      $model = new Remito_adquisicion();


        $orden = Yii::$app->request->post()['OrdenCompraSearch'];
        $orden_nro = $orden['OC_NRO'];
        $pedido_nro = $orden['OC_PEDADQ'];

        //Modificar renglones Orden con codigo y deposito
        $orden_compra = OrdenCompra::findOne($orden_nro);
        
        $pedido_adqu = Pedido_adquisicion::findOne($pedido_nro);

        $model->RA_FECHA = date('Y-m-d');
        $model->RA_HORA = date('H:i:s');
        $model->RA_TIPMOV = 'O';
        $model->RA_DEPOSITO = $pedido_adqu->PE_DEPOSITO;
        $model->pedido = $pedido_nro;
        $model->RA_OCNRO = $orden_nro;
        $model->RA_CONCEP = "INGRESADO CON ORDEN COMPRA Nº ".$orden_compra->numero.'-'.$orden_compra->ejercicio;
        
        foreach ($orden_compra->renglones as $key => $value) {
          
          $articulo = ArticGral::findOne(['AG_CODRAF'=>$value->EN_CODRAFAM,'AG_DEPOSITO'=>$model->RA_DEPOSITO]);
         
          if (isset($articulo)){
            $value->EN_CODART=$articulo->AG_CODIGO;
            $value->EN_DEPOSITO = $model->RA_DEPOSITO;
            $value->save();
          }
        }

        $items = array();
        foreach ($orden_compra->renglones as $key => $value) {
          
          if (isset($value->EN_CODART) && !empty($value->EN_CODART)){
           
            $item = new Remito_adquisicion_renglones();
            $item->AR_NROREN = $value->EN_ITEM;
            $item->AR_CANTID = $value->EN_CANT;
            $item->AR_CODART = $value->EN_CODART;
            $item->descripcion = $value->articulo->AG_NOMBRE;
            $item->precio_compra = $value->EN_COSTO;
            $items[] = $item;  
          }      
        }
        
        $model->renglones = $items;
        return $this->render('create_orden_compra', [
            'model' => $model,
            
        ]);
    }

    public function actionCreate_orden_compra()
    {
        $model = new Remito_adquisicion();
        $model->scenario = 'create_orden';

        if ($model->load(Yii::$app->request->post())) {

             $model->RA_TIPMOV = 'O';
            if ($model->validate() ){            
        
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                     $model->RA_CODOPE = Yii::$app->user->identity->LE_NUMLEGA;
                    
                    //Se guarda encabezado Remito
                       if ($model->save()){
                        $this->guardar_renglones($model);
                       }else{
                        $mensaje = ""; 
                        foreach ($model->getFirstErrors() as $key => $value) {
                          $mensaje .= "$value \\n\\r";
                        }
                        throw new ErrorException($mensaje);
                       }
                    
                    $this->generarPdf($model->RA_NUM);

                    //Se verifica si todas las cantidades de la OC fueron recibidas
                    $orden_compra = OrdenCompra::findOne($model->RA_OCNRO);
                    if (!isset($orden_compra->OC_PEDADQ))
                      $orden_compra->OC_PEDADQ = $model->pedido;

                    $orden_compra->chequear_finalizada();
                    $orden_compra->save();

                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('exito_deposito_central', 'El Remito fue generado con éxito.');
                    return $this->redirect(['view', 'id' => $model->RA_NUM]);
                  
                }
                catch (\Exception $e) {
                    $transaction->rollBack();
                    
                    Yii::$app->getSession()->setFlash('error_deposito_central', $e->getMessage());

                    return $this->render('create_orden_compra', [
                    'model' => $model,
                    ]);
                }
            }
            else{
       
              return $this->render('create_orden_compra', [
                'model' => $model,
               ]);
            }
          }
         else {
            $id = Yii::$app->request->get()['id']; 
            $model->RA_FECHA = date('Y-m-d');
            $model->RA_HORA = date('H:i:s');
            $model->RA_TIPMOV = 'O';
            $orden_compra = OrdenCompra::findOne($id);

            $model->pedido = $orden_compra->OC_PEDADQ;
            $model->RA_OCNRO = $orden_compra->OC_NRO;
            $model->RA_DEPOSITO = $orden_compra->pedido->PE_DEPOSITO;
            $model->RA_CONCEP = "INGRESADO CON ORDEN COMPRA Nº ".$orden_compra->numero.'-'.$orden_compra->ejercicio;
            
            $items = array();
            foreach ($orden_compra->renglones as $key => $value) {
                $item = new Remito_adquisicion_renglones();
                $item->AR_NROREN = $value->EN_ITEM;
                //$item->AR_CANTID = $value->EN_CANT;
                $item->AR_CANTID = $model->cantidad_pendiente($value->EN_CODART,$model->RA_DEPOSITO);
                $item->AR_CODART = $value->EN_CODART;
                $item->descripcion = $value->articulo->AG_NOMBRE;
                //$item->AR_FECVTO = $value->RS_FECVTO;
                $item->precio_compra = $value->EN_COSTO;
                $items[] = $item;        
            }
            
            $model->renglones = $items;
            return $this->render('create_orden_compra', [
                'model' => $model,
                
            ]);
        }
    }

    public function actionCreate_adquisicion()
    {
        $model = new Remito_adquisicion();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() ){
                  $connection = \Yii::$app->db;
                  $transaction = $connection->beginTransaction();

                  try {
                      //Se guarda encabezado Remito
                    
                      $model->RA_CODOPE = Yii::$app->user->identity->LE_NUMLEGA;
                      if ($model->save()){
                        $this->guardar_renglones($model);
                       }else{
                        $mensaje = ""; 
                        foreach ($model->getFirstErrors() as $key => $value) {
                          $mensaje .= "$value \\n\\r";
                        }
                        
                        throw new ErrorException($mensaje);
                       }
                     
                      $this->generarPdf($model->RA_NUM);

                      $transaction->commit();
                      Yii::$app->getSession()->setFlash('exito_deposito_central', 'La adquisición se registro con exito!.');
                      //Yii::$app->session->setFlash('success', 'Movimientos Guardados con éxito.');
                      return $this->redirect(['view', 'id' => $model->RA_NUM]);
                      
                  }
                  catch (\Exception $e) {
                      $transaction->rollBack();
                      
                      Yii::$app->getSession()->setFlash('error_deposito_central', $e->getMessage());

                      return $this->render('create_adquisicion', [
                      'model' => $model,
                      ]);
                      //throw $e;
                  }
                }
            else{
              return $this->render('create_adquisicion', [
                'model' => $model,
              
               
            ]);
            }
        
           
        } else {
            $model->RA_FECHA = date('Y-m-d');
            $model->RA_HORA = date('H:i:s');
            // $items = [];
            // $item = new Remito_adquisicion_renglones();
            // $item->AR_NROREN = 1;
            // $item->descripcion = "";
            // array_push($items,$item);
           
            // $renglones= new ArrayDataProvider([
            //     'allModels' => $items,
            // ]);
            // $model->renglones = $renglones;
            return $this->render('create_adquisicion', [
                'model' => $model,
              
               
            ]);
        }
    }

    /**
     * Updates an existing Remito_Adquisicion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->RA_NUM]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Remito_Adquisicion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Remito_Adquisicion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Remito_Adquisicion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Remito_adquisicion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('No existe el Remito de Adquisición.');
        }
    }

    private function generarPdf($id){
      
      $model = $this->findModel($id);
      $searchModel = new Remito_adquisicion_renglones();
      $renglones =  $searchModel->get_renglones($id);

      $content = $this->renderPartial('impresion', ['model' => $model,'renglones' => $renglones]);
      
      $nombre = $id."_". date('d-m-Y')."_".date('Hi').".pdf";

      $nombre = Yii::$app->params['local_path']['path_deposito_central']."/adquisiciones/".$nombre;

      // setup kartik\mpdf\Pdf component
      $pdf = new Pdf([
          'mode' => Pdf::MODE_UTF8,
          'format' => Pdf::FORMAT_A4, 
          'orientation' => Pdf::ORIENT_PORTRAIT, 
          'filename' => $nombre,
          'destination' => Pdf::DEST_FILE, 
          'content' => $content,
          'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
          'cssInline' => '.kv-heading-1{font-size:18px}', 
          'options' => ['title' => 'Remito de Adquisición'],
      ]);
      
      $pdf->render();

    }

    public function actionReport($id) {

      header('Content-Type: application/pdf');
      
      $model = $this->findModel($id);
      $searchModel = new Remito_adquisicion_renglones();
      $renglones =  $searchModel->get_renglones($id);

      $content = $this->renderPartial('impresion', ['model' => $model,'renglones' => $renglones]);
      
      // setup kartik\mpdf\Pdf component
      $pdf = new Pdf([
          // set to use core fonts only
          //'mode' => Pdf::MODE_CORE, 
          'mode' => Pdf::MODE_UTF8,
          // A4 paper format
          'format' => Pdf::FORMAT_A4, 
          // portrait orientation
          'orientation' => Pdf::ORIENT_PORTRAIT, 
          // stream to browser inline
          //'filename' => $nombre,
          'destination' => Pdf::DEST_BROWSER, 
          // your html content input
          'content' => $content,
          // format content from your own css file if needed or use the
          // enhanced bootstrap css built by Krajee for mPDF formatting 
          'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
          // any css to be embedded if required
          'cssInline' => '.kv-heading-1{font-size:18px}
                          .col-md-5{float:right;}', 
           // set mPDF properties on the fly
          'options' => ['title' => 'Remito de Adquisición'],
           // call mPDF methods on the fly
          // 'methods' => [ 
          //     'SetHeader'=>['deposito_central - Hospital Municipal de Agudos Dr. Leónidas Lucero'], 
          //     'SetFooter'=>['{PAGENO}'],
          // ]
      ]);
     
       return $pdf->render(); 
  }

  public function actionReport2($id)
  {
      // Set alias for sample directory
      Yii::setAlias('example', '@vendor/chrmorandi/yii2-jasper/examples');

      /* @var $jasper Jasper */
      $jasper = Yii::$app->jasper;

      // Compile a JRXML to Jasper
      $jasper->compile(Yii::getAlias('@example') . '/prueba.jrxml')->execute();

      // Process a Jasper file to PDF and RTF (you can use directly the .jrxml)
      $jasper->process(
          Yii::getAlias('@example') . '/prueba.jasper', 
          ['php_version' => 'xxx'],
          ['pdf'],
          false, 
          false 
      )->execute();

      // List the parameters from a Jasper file.
      $array = $jasper->listParameters(Yii::getAlias('@example') . '/prueba.jasper')->execute();

      // return pdf file
      Yii::$app->response->sendFile(Yii::getAlias('@example') . '/prueba.pdf');

  }

   public function actionValidateAsociarPedido()
    {
        $model = new OrdenCompraSearch(['scenario' => 'asociar_pedido']);
        
        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
 }
<?php

namespace deposito_central\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use deposito_central\models\Proveedores;
use deposito_central\models\OrdenCompra;
use deposito_central\models\OrdenCompra_renglones;
use yii\base\ErrorException;
/**
 * AlarmaController implements the CRUD actions for Alarma model.
 */
class Importar_rafamController extends Controller
{
      public $CodController="026";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::classname(),
                'only'=>["importar"],
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=> ['@'],
                        'matchCallback' => 
                            function ($rule, $action) {
                                return Yii::$app->user->identity->habilitado($action);
                            }
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {   
        return $this->render('index', ['resultado'=>''
            
            ]);
    }

     public function actionImportar_proveedores()
    {   
        set_time_limit(0);
        
        try{
            $conn = oci_connect('OWNER_RAFAM', 'OWNERDBA', "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST =192.168.0.18)(PORT = 1521)))(CONNECT_DATA=(SID=HMABB)))");
              
            if (!$conn) {
                $e = oci_error();
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
            //Importo los proveedores
            $sql= "SELECT * from proveedores";

            $stid_provee = oci_parse($conn, $sql);
            oci_execute($stid_provee);
            $proveedores_importados = 0;
            while ($row = oci_fetch_array($stid_provee, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $proveedor = Proveedores::findOne($row['COD_PROV']);
                  //Si no existe el proveedor lo importo
                if (!isset($proveedor)){
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
                    $proveedores_importados++;
                }
            }

            return \yii\helpers\Json::encode("Se importaron $proveedores_importados Proveedores");

            }
        catch (\Exception $e) {
           
            throw $e;
            return \yii\helpers\Json::encode("ERROR".$e->message);
        }
    }

    private function guardar_renglones_orden($numero,$ejercicio){
      $oc_nro = $ejercicio.str_pad( $numero, 6, "0", STR_PAD_LEFT);
      $connection_rafam = \Yii::$app->dbRafam;
      
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

      //Importo los renglones
      $command = $connection_rafam->createCommand($sql);
      $renglones = $command->queryAll();
      foreach ($renglones as $key => $row) {
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

    public function actionImportar_ordenes()
    {   
      set_time_limit(0);
      $connection_rafam = \Yii::$app->dbRafam;
      
        try{
            $connection = \Yii::$app->db;
            $query = OrdenCompra::find();
            $ordenes = $query->select(['OC_NRO'])->all();
           
            $primer = true;
            
            $conjunto_ordenes = array();
            foreach ($ordenes as $key => $orden) {
                $numero = substr($orden->OC_NRO,-6) * 1;
                $ejercicio = substr($orden->OC_NRO,0,4);
                
                $conjunto_ordenes[]= $orden->OC_NRO;
               
            }
            $conjunto_ordenes = "['".$separado_por_comas = implode("','", $conjunto_ordenes)."']";
            
            //where CONCAT(oc.EJERCICIO,LPAD(oc.NRO_OC, 6, '0')) IN ['2008000001']
            $año_actual = date ("Y");
            $sql= "SELECT oc.NRO_OC,oc.EJERCICIO,TO_CHAR(oc.FECH_OC, 'YYYY-MM-DD') as FECHA_OC,
                        oc.COD_PROV
                  FROM orden_compra oc
                  where oc.EJERCICIO=$año_actual
                  ORDER BY oc.EJERCICIO,oc.NRO_OC";
            
            
            $command = $connection_rafam->createCommand($sql);
            $ordenes = $command->queryAll();
            //Importo los Ordenes de Compra
            // $sql= "SELECT oc.NRO_OC,oc.EJERCICIO,TO_CHAR(oc.FECH_OC, 'YYYY-MM-DD') as FECHA_OC,
            //             oc.COD_PROV
            //       FROM orden_compra oc
            //       WHERE ROWNUM<11
            //       ORDER BY oc.EJERCICIO,oc.NRO_OC";

           
            $ordenes_importadas = 0;
            
            foreach ($ordenes as $key => $row) {
                $oc_nro = $row['EJERCICIO'].str_pad( $row['NRO_OC'], 6, "0", STR_PAD_LEFT);
                $orden_compra = OrdenCompra::findOne($oc_nro);
                if (!isset($orden_compra)){
                  $oc_proveed = $row['COD_PROV'];
                  $oc_fecha =   $row['FECHA_OC'];
                  $orden_compra = OrdenCompra::findOne($oc_nro);
                  if (!isset($orden_compra)){
                      $orden_compra = new OrdenCompra();
                      $orden_compra->OC_NRO = $oc_nro;
                      $orden_compra->OC_PROVEED = $oc_proveed;
                      $this->verificar_proveedor($oc_proveed);
                      $orden_compra->OC_FECHA =  $oc_fecha;
                      $orden_compra->OC_FINALIZADA = 0; 
                      $transaction = $connection->beginTransaction();
                      if (!$orden_compra->save()){
                          $mensaje = ""; 
                          foreach ($orden_compra->getFirstErrors() as $key => $value) {
                            $mensaje .= "$value \\n\\r";
                          }
                          
                          throw new ErrorException($mensaje);
                      }else{
                        $this->guardar_renglones_orden($row['NRO_OC'],$row['EJERCICIO']);
                      }
                      $transaction->commit();
                     $ordenes_importadas++;
                  }
                }
                
            }
           
            return \yii\helpers\Json::encode("Se importaron $ordenes_importadas Ordenes de compra del año ".$año_actual);
        }
        catch (\Exception $e) {
            
            throw $e;
            return \yii\helpers\Json::encode("ERROR".$e->message);
        }
    }    
}

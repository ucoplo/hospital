<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Remito_adquisicion;
use farmacia\models\Remito_adquisicion_renglones;
use farmacia\models\Remito_adquisicionSearch;
use farmacia\models\Remito_depositoSearch;
use farmacia\models\Remito_deposito;
use farmacia\models\Vencimientos;
use farmacia\models\Movimientos_diarios;
use farmacia\models\ArticGral;

use kartik\mpdf\Pdf;
use chrmorandi\jasper\Jasper;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\ErrorException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider ;
use yii\base\Model;
use yii\filters\AccessControl;
/**
 * Remito_AdquisicionController implements the CRUD actions for Remito_Adquisicion model.
 */
class Remito_adquisicionController extends Controller
{ 

    public $CodController="002";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
        {
            return [
                'access'=>[
                    'class'=>AccessControl::classname(),
                    'only'=>['index','view',
                    'seleccion_remito_deposito','create_remito_deposito','create_externo'],

            // Los que no estén en este listado por defecto
            // van a tener acceso permitido.
            // Por ejemplo en este caso la acción "index"
            // se podrá acceder sin loguin

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

    /**
     * Creates a new Remito_Adquisicion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSeleccion_remito_deposito()
    {
        $searchModel = new Remito_depositoSearch();
        $params[$searchModel->formName()]['RS_IMPORT'] = 'F'; //Remitos de Deposito sin importar
        $dataProvider = $searchModel->search($params);


        return $this->render('seleccion_remito', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        
    }

    private function guardar_renglones($model)
    {

      try {
       $num_renglon = 1;
       foreach ($model->renglones as $key => $obj) {
          $renglon = new Remito_adquisicion_renglones();

          $renglon->RM_RENUM = $model->RE_NUM;
          $renglon->RM_DEPOSITO = $model->RE_DEPOSITO;
          $renglon->RM_NUMRENG = $num_renglon;
          $renglon->RM_CODMON = $obj['RM_CODMON'];
          $renglon->RM_PRECIO = $obj['precio_compra'];
          $renglon->RM_CANTID = $obj['RM_CANTID'];
          $renglon->RM_FECVTO = $obj['RM_FECVTO'];
          
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
          $vencimiento = Vencimientos::find()->where(['TV_CODART' => $renglon->RM_CODMON,
                                                      'TV_FECVEN' => $renglon->RM_FECVTO,
                                                      'TV_DEPOSITO' => $renglon->RM_DEPOSITO,
                                                      ])->one();

          if (count($vencimiento)==1){
              $vencimiento->TV_SALDO += $renglon->RM_CANTID;

          }
          else{
              $vencimiento = new Vencimientos();
              $vencimiento->TV_CODART = $renglon->RM_CODMON;
              $vencimiento->TV_FECVEN = $renglon->RM_FECVTO;
              $vencimiento->TV_SALDO = $renglon->RM_CANTID;
              $vencimiento->TV_DEPOSITO = $renglon->RM_DEPOSITO;
          }

          
          if (!$vencimiento->save()){
            $mensaje = ""; 
            foreach ($vencimiento->getFirstErrors() as $key => $value) {
              $mensaje .= "\\n\\r $value";
            }
            
            throw new ErrorException($mensaje);
          }

          //Registramos el Movimiento Diario
          $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->RM_CODMON,
                                                      'MD_FECVEN' => $renglon->RM_FECVTO,
                                                      'MD_DEPOSITO' => $renglon->RM_DEPOSITO,
                                                      'MD_CODMOV' => "C",
                                                      'MD_FECHA' => $model->RE_FECHA,
                                                      ])->one();
                                     
       
          if ($movimiento){
              $movimiento->MD_CANT += $renglon->RM_CANTID;
          }
          else{

              $movimiento = new Movimientos_diarios();
              $movimiento->MD_FECHA = $model->RE_FECHA;
              $movimiento->MD_CODMOV = "C";
              $movimiento->MD_CANT = $renglon->RM_CANTID;
              $movimiento->MD_FECVEN =  $renglon->RM_FECVTO;
              $movimiento->MD_CODMON = $renglon->RM_CODMON;
              $movimiento->MD_DEPOSITO = $renglon->RM_DEPOSITO;

          }

          if (!$movimiento->save()){
            $mensaje = ""; 
            foreach ($movimiento->getFirstErrors() as $key => $value) {
              $mensaje .= "\\n\\r $value";
            }
            
            throw new ErrorException($mensaje);
          }

          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->RM_CODMON,
                                                      'AG_DEPOSITO' => $renglon->RM_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              if (isset($model->RE_REMDEP) && !empty($model->RE_REMDEP)){
                $artic_gral->AG_PRECIO=$obj['precio_compra'];
              }
              $artic_gral->AG_STACT += $renglon->RM_CANTID;

              $artic_gral->AG_ULTENT = date('Y-m-d');

              if (!$artic_gral->save()){
                $mensaje = ""; 
                foreach ($artic_gral->getFirstErrors() as $key => $value) {
                  $mensaje .= "\\n\\r $value";
                }
                
                throw new ErrorException($mensaje);
              }
              
          }


       }

       //Se marca el remito de Deposito como importado
       if (isset($model->RE_REMDEP) && !empty($model->RE_REMDEP)){
         $remito_deposito = Remito_deposito::findOne($model->RE_REMDEP);
         $remito_deposito->RS_IMPORT = 'T';
         
         if (!$remito_deposito->save()){
            $mensaje = ""; 
            foreach ($remito_deposito->getFirstErrors() as $key => $value) {
              $mensaje .= "\\n\\r $value";
            }
            
            throw new ErrorException($mensaje);
          }
       }
      }
      catch (\Exception $e) {
         throw $e;
      }                   


    }
     public function actionCreate_remito_deposito($id)
    {
        $model = new Remito_adquisicion();

        if ($model->load(Yii::$app->request->post())) {

             $model->RE_TIPMOV = 'T';
            if ($model->validate() ){            
        
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                     $model->RE_CODOPE = Yii::$app->user->identity->LE_NUMLEGA;
                    
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

                    
                    $this->generarPdf($model->RE_NUM);
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('exito_farmacia', 'El Remito fue generado con éxito.');
                    return $this->redirect(['view', 'id' => $model->RE_NUM]);
                  
                }
                catch (\Exception $e) {
                    $transaction->rollBack();
                    
                    Yii::$app->getSession()->setFlash('error_farmacia', $e->getMessage());

                    return $this->render('create_externo', [
                    'model' => $model,
                    ]);
                }
            }
            else{
       
              return $this->render('create_deposito', [
                'model' => $model,
               ]);
            }
          }
         else {

            $model->RE_FECHA = date('Y-m-d');
            $model->RE_HORA = date('H:i:s');
            $model->RE_TIPMOV = 'T';
            $remito_deposito = Remito_deposito::findOne($id);

            $model->RE_DEPOSITO = $remito_deposito->RS_CODEP;
            $model->RE_REMDEP = $remito_deposito->RS_NROREM;
            $model->RE_CONCEP = "PROVIENE DE DEPOSITO CENTRAL REMITO Nº ".$remito_deposito->RS_NROREM;
            
            $items = array();
            foreach ($remito_deposito->rsRengs as $key => $value) {
                $item = new Remito_adquisicion_renglones();
                $item->RM_NUMRENG = $value->RS_NUMRENG;
                $item->RM_CANTID = $value->RS_CANTID;
                $item->RM_CODMON = $value->RS_CODMON;
                $item->descripcion = $value->rSCODMON->AG_NOMBRE;
                $item->RM_FECVTO = $value->RS_FECVTO;
                $item->precio_compra = $value->RS_VALULTCOMP;
                $items[] = $item;        
            }
            
            $model->renglones = $items;
            return $this->render('create_deposito', [
                'model' => $model,
                
            ]);
        }
    }

    public function actionCreate_externo()
    {
        $model = new Remito_adquisicion();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() ){
                  $connection = \Yii::$app->db;
                  $transaction = $connection->beginTransaction();

                  try {
                      //Se guarda encabezado Remito
                    
                      $model->RE_CODOPE = Yii::$app->user->identity->LE_NUMLEGA;
                      if ($model->save()){
                         
                        $this->guardar_renglones($model);
                       }else{
                        $mensaje = ""; 
                        foreach ($model->getFirstErrors() as $key => $value) {
                          $mensaje .= "$value \\n\\r";
                        }
                        
                        throw new ErrorException($mensaje);
                       }
                     
                      $this->generarPdf($model->RE_NUM);

                      $transaction->commit();
                      Yii::$app->getSession()->setFlash('exito_farmacia', ['type' => 'success']);
                      //Yii::$app->session->setFlash('success', 'Movimientos Guardados con éxito.');
                      return $this->redirect(['view', 'id' => $model->RE_NUM]);
                      
                  }
                  catch (\Exception $e) {
                      $transaction->rollBack();
                      
                      Yii::$app->getSession()->setFlash('error_farmacia', $e->getMessage());

                      return $this->render('create_externo', [
                      'model' => $model,
                      ]);
                      //throw $e;
                  }
                }
            else{
              return $this->render('create_externo', [
                'model' => $model,
              
               
            ]);
            }
        
           
        } else {
            $model->RE_FECHA = date('Y-m-d');
            $model->RE_HORA = date('H:i:s');
            // $items = [];
            // $item = new Remito_adquisicion_renglones();
            // $item->RM_NUMRENG = 1;
            // $item->descripcion = "";
            // array_push($items,$item);
           
            // $renglones= new ArrayDataProvider([
            //     'allModels' => $items,
            // ]);
            // $model->renglones = $renglones;
            return $this->render('create_externo', [
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
            return $this->redirect(['view', 'id' => $model->RE_NUM]);
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

      $nombre = Yii::$app->params['local_path']['path_farmacia']."/adquisiciones/".$nombre;

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
          //     'SetHeader'=>['Farmacia - Hospital Municipal de Agudos Dr. Leónidas Lucero'], 
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
}

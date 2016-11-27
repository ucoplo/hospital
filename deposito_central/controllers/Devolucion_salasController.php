<?php

namespace deposito_central\controllers;

use Yii;
use deposito_central\models\Devolucion_salas;
use deposito_central\models\Devolucion_salas_renglones;
use deposito_central\models\Devolucion_salasSearch;
use deposito_central\models\Planilla_entrega;
use deposito_central\models\Planilla_entregaSearch;
use deposito_central\models\Planilla_entrega_renglones;
use deposito_central\models\Vencimientos;
use deposito_central\models\Movimientos_diarios;
use deposito_central\models\Movimientos_sala;
use deposito_central\models\Movimientos_quirofano;
use deposito_central\models\ArticGral;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Devolucion_salasController implements the CRUD actions for Devolucion_salas model.
 */
class Devolucion_salasController extends Controller
{
    public $CodController="014";
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
                        'actions'=>['index','create','view','seleccion_remito',
                               'iniciar_creacion','report'],
                        'matchCallback' => 
                            function ($rule, $action) {
                                return Yii::$app->user->identity->habilitado($action);
                            }
                    ],
                    [
                        'allow'=>true,
                        'roles'=> ['@'],
                        'actions' => ['vencimiento_codart_remito',],
                        
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Devolucion_salas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Devolucion_salasSearch();
        $params = Yii::$app->request->queryParams;
        $params[$searchModel->formName()]['DE_SOBRAN'] = 0;
        $dataProvider = $searchModel->search($params);
       
        $dataProvider->setSort(false);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Devolucion_salas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

     public function actionSeleccion_remito()
    {
        $searchModel = new Planilla_entregaSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false);

        return $this->render('seleccion_remito', [
            'dataProvider' => $dataProvider,
        ]);

        
    }

    public function actionIniciar_creacion($remito)
    {
         $model = new Devolucion_salas();

         $remito_granel = Planilla_entrega::findOne($remito);

         $model->DE_SERSOL = $remito_granel->PE_SERSOL;
         $model->DE_DEPOSITO = $remito_granel->PE_DEPOSITO;
         $model->DE_FECHA = date('Y-m-d');
         $model->DE_HORA = date('H:i:s');
         $model->DE_CODOPE = Yii::$app->user->identity->LE_NUMLEGA; //El usuario logueado
         $model->DE_ENFERM = $remito_granel->PE_ENFERM;
         $model->DE_NUMREMOR = $remito_granel->PE_NROREM;
         
                 
         $model->renglones = [];

         return $this->render('create', [
                'model' => $model,
            ]);
    }

    private function guardar_renglones($model)
    {

      try {
       $num_renglon = 1;
       foreach ($model->renglones as $key => $obj) {
          $renglon = new Devolucion_salas_renglones();

          $renglon->PR_NRODEVOL = $model->DE_NRODEVOL;
          $renglon->PR_DEPOSITO = $model->DE_DEPOSITO;
          $renglon->PR_CODART = $obj['PR_CODART'];
          $renglon->PR_CANTID = $obj['PR_CANTID'];
          $renglon->PR_FECVTO =  date('Y-m-d', strtotime(str_replace("/","-",$obj['PR_FECVTO'])));
          
          //Se guarda cada renglón del Remito
          
          if (!$renglon->save()){
            $mensaje = ""; 
            foreach ($renglon->getFirstErrors() as $key => $value) {
              $mensaje .= "\\n\\r $value";
            }
            
            throw new ErrorException($mensaje);
          }
          $num_renglon++;                           

          //Decrementamos la cantidad en Vencimientos
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
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->PR_CODART,
                                                      'AG_DEPOSITO' => $renglon->PR_DEPOSITO,
                                                      ])->one();
          if ($artic_gral){
              $artic_gral->AG_STACDEP += $renglon->PR_CANTID;
              $artic_gral->AG_ULTENT = date('Y-m-d');
              
              if (!$artic_gral->save()){
                $mensaje = ""; 
                foreach ($artic_gral->getFirstErrors() as $key => $value) {
                  $mensaje .= "\\n\\r $value";
                }
                
                throw new ErrorException($mensaje);
              }
          }
          //Si se fracciona en Quirofana la cantidad se multiplica por las unidades del envase
          if ($artic_gral->AG_FRACCQ=='S'){
            $cantidad_entrada = $renglon->PR_CANTID*$artic_gral->AG_UNIENV;
          }else{
            $cantidad_entrada = $renglon->PR_CANTID;
          }

          $servicios_quirofano = Yii::$app->params['servicios_quirofano'];
          if (in_array($model->DE_SERSOL,$servicios_quirofano )){

            $movimiento_quirofano = Movimientos_quirofano::find()->where(['MO_CODART' => $renglon->PR_CODART,
                                                        'MO_DEPOSITO' =>$renglon->PR_DEPOSITO,
                                                        'MO_TIPMOV' => "E",
                                                        'MO_FECHA' => $model->DE_FECHA,
                                                        'MO_IDFOJA' => null,
                                                        'MO_SECTOR' => $model->DE_SERSOL,
                                                        ])->one();
            if ($movimiento_quirofano){
                $movimiento_quirofano->MO_CANTIDA += $cantidad_entrada;
            }
            else{$movimiento_quirofano = new Movimientos_quirofano();
                $movimiento_quirofano->MO_FECHA = $model->DE_FECHA;
                $movimiento_quirofano->MO_HORA = $model->DE_HORA;
                $movimiento_quirofano->MO_SECTOR = $model->DE_SERSOL;
                $movimiento_quirofano->MO_TIPMOV = "E";
                $movimiento_quirofano->MO_CANTIDA = $cantidad_entrada;
                $movimiento_quirofano->MO_CODART = $renglon->PR_CODART;
                $movimiento_quirofano->MO_DEPOSITO = $renglon->PR_DEPOSITO;
               
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
              
            $movimiento_sala = Movimientos_sala::find()->where(['MO_CODMON' => $renglon->PR_CODART,
                                                          'MO_DEPOSITO' => $renglon->PR_DEPOSITO,
                                                          'MO_TIPMOV' => 'G',
                                                          'MO_FECHA' => $model->DE_FECHA,
                                                          'MO_CODSERV' => $model->DE_SERSOL,
                                                          'MO_HISCLI' => null,
                                                          ])->one();
            if ($movimiento_sala){
                $movimiento_sala->MO_CANT += $renglon->PR_CANTID;
            }
            else{
                $movimiento_sala = new Movimientos_sala();
                $movimiento_sala->MO_FECHA = $model->DE_FECHA;
                $movimiento_sala->MO_HORA = $model->DE_HORA;
                $movimiento_sala->MO_TIPMOV = 'G';
                $movimiento_sala->MO_CANT = $renglon->PR_CANTID;
                $movimiento_sala->MO_CODMON = $renglon->PR_CODART;
                $movimiento_sala->MO_DEPOSITO = $renglon->PR_DEPOSITO;
                $movimiento_sala->MO_CODSERV =  $model->DE_SERSOL;
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

          //Registramos el Movimiento Diario
          $movimiento = Movimientos_diarios::find()->where(['DM_CODART' => $renglon->PR_CODART,
                                                      'DM_FECVTO' => $renglon->PR_FECVTO,
                                                      'DM_DEPOSITO' => $renglon->PR_DEPOSITO,
                                                      'DM_CODMOV' => "D",
                                                      'DM_FECHA' => $model->DE_FECHA,
                                                      ])->one();
                                     
       
          if ($movimiento){
              $movimiento->DM_CANT += $renglon->PR_CANTID;
          }
          else{
              $movimiento = new Movimientos_diarios();
              $movimiento->DM_FECHA = $model->DE_FECHA;
              $movimiento->DM_CODMOV = "D";
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

          
       }
      }
      catch (\Exception $e) {
         throw $e;
      }  

    }
    /**
     * Creates a new Devolucion_salas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Devolucion_salas();
        $model->scenario = "create";

        if ($model->load(Yii::$app->request->post())) {

             if ($model->validate() ){            
        
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                    
                    //Se guarda encabezado Devolucion
                     if ($model->save()){
                         $this->guardar_renglones($model);
                      }else{
                        $mensaje = ""; 
                        foreach ($model->getFirstErrors() as $key => $value) {
                          $mensaje .= "$value \\n\\r";
                        }
                        throw new ErrorException($mensaje);
                      }

                    $this->generarPdf($model->DE_NRODEVOL);
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->DE_NRODEVOL]);
    
                }
                catch (\Exception $e) {
                  $transaction->rollBack();
                  
                  Yii::$app->getSession()->setFlash('error', $e->getMessage());

                  return $this->render('create', [
                  'model' => $model,
                  ]);
                    $transaction->rollBack();
                    throw $e;
                }
           
            }
            else{
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

    /**
     * Updates an existing Devolucion_salas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->DE_NRODEVOL]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Devolucion_salas model.
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
     * Finds the Devolucion_salas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Devolucion_salas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {   
        if (($model = Devolucion_salas::findOne($id)) !== null) {
            $searchModel = new Devolucion_salas_renglones();
            $model->renglones =  $searchModel->get_renglones($id);
            
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        
    }

      /*
    Obtiene la fecha de vencimiento mas antigua de un medicamento
    */
    public function actionVencimiento_codart_remito()
    {

      $codart = $_POST['codart'];
      $deposito = $_POST['deposito'];
      $remito = $_POST['remito'];

      $query = Planilla_entrega_renglones::find()->where(['PR_CODART' => $codart,
                                                             'PR_DEPOSITO' => $deposito,
                                                             'PR_NROREM' => $remito]);

      $vencimiento = $query->one(); 

       

       if ($vencimiento){
         $fecha_vto= Yii::$app->formatter->asDate($vencimiento->PR_FECVTO,'php:d-m-Y');
         $vencimiento_result['fecha'] = $fecha_vto;
         
       }
       else{
         $fecha_vto='';
         $vencimiento_result['fecha'] = '';
       }

       

       return \yii\helpers\Json::encode($vencimiento_result);
        
    }

      private function generarPdf($id){
      
      $model = $this->findModel($id);

      $content = $this->renderPartial('impresion', ['model' => $model]);
      
      $nombre = $id."_". date('d-m-Y')."_".date('Hi').".pdf";

      $nombre = Yii::$app->params['local_path']['path_deposito_central']."/devoluciones_salas/".$nombre;

      $pdf = new Pdf([
          'mode' => Pdf::MODE_UTF8,
          'format' => Pdf::FORMAT_A4, 
          'orientation' => Pdf::ORIENT_PORTRAIT, 
          'filename' => $nombre,
          'destination' => Pdf::DEST_FILE, 
          'content' => $content,
          'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
          'cssInline' => '.kv-heading-1{font-size:18px}', 
          'options' => ['title' => 'Vale Ventanilla'],
      ]);
      
      $pdf->render();

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
           'options' => ['title' => 'Vale Ventanilla'],
      ]);
     
      return $pdf->render(); 
    }
}

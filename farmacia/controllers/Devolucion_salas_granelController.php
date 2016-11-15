<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Devolucion_salas_granel;
use farmacia\models\Devolucion_salas_granel_renglones;
use farmacia\models\Devolucion_salas_granelSearch;
use farmacia\models\Consumo_medicamentos_granel;
use farmacia\models\Consumo_medicamentos_granelSearch;
use farmacia\models\Consumo_medicamentos_granel_renglones;
use farmacia\models\Vencimientos;
use farmacia\models\Movimientos_diarios;
use farmacia\models\Movimientos_sala;
use farmacia\models\Movimientos_quirofano;
use farmacia\models\ArticGral;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Devolucion_salas_granelController implements the CRUD actions for Devolucion_salas_granel model.
 */
class Devolucion_salas_granelController extends Controller
{
    public $CodController="006";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::classname(),
                'only'=>['index','create','iniciar_creacion','seleccion_remito','view'],
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
     * Lists all Devolucion_salas_granel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Devolucion_salas_granelSearch();
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
     * Displays a single Devolucion_salas_granel model.
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
        $searchModel = new Consumo_medicamentos_granelSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false);

        return $this->render('seleccion_remito', [
            'dataProvider' => $dataProvider,
        ]);

        
    }

    public function actionIniciar_creacion($remito)
    {
         $model = new Devolucion_salas_granel();

         $remito_granel = Consumo_medicamentos_granel::findOne($remito);

         $model->DE_SERSOL = $remito_granel->CM_SERSOL;
         $model->DE_DEPOSITO = $remito_granel->CM_DEPOSITO;
         $model->DE_FECHA = date('Y-m-d');
         $model->DE_HORA = date('H:i:s');
         $model->DE_CODOPE = Yii::$app->user->identity->LE_NUMLEGA; //El usuario logueado
         $model->DE_ENFERM = $remito_granel->CM_ENFERM;
         $model->DE_NUMREMOR = $remito_granel->CM_NROREM;
         
                 
         $model->renglones = [];

         return $this->render('create', [
                'model' => $model,
            ]);
    }

    private function guardar_renglones($model)
    {

     
       $num_renglon = 1;
       foreach ($model->renglones as $key => $obj) {
          $renglon = new Devolucion_salas_granel_renglones();

          $renglon->DF_NRODEVOL = $model->DE_NRODEVOL;
          $renglon->DF_DEPOSITO = $model->DE_DEPOSITO;
          $renglon->DF_CODMON = $obj['DF_CODMON'];
          $renglon->DF_CANTID = $obj['DF_CANTID'];
          $renglon->DF_FECVTO =  date('Y-m-d', strtotime(str_replace("/","-",$obj['DF_FECVTO'])));
          
          //Se guarda cada renglón del Remito
          $renglon->save();
          $num_renglon++;                           

          //Decrementamos la cantidad en Vencimientos
          $vencimiento = Vencimientos::find()->where(['TV_CODART' => $renglon->DF_CODMON,
                                                      'TV_FECVEN' => $renglon->DF_FECVTO,
                                                      'TV_DEPOSITO' => $renglon->DF_DEPOSITO,
                                                      ])->one();
          if (count($vencimiento)==1){
              $vencimiento->TV_SALDO += $renglon->DF_CANTID;
              $vencimiento->save();
          }
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->DF_CODMON,
                                                      'AG_DEPOSITO' => $renglon->DF_DEPOSITO,
                                                      ])->one();
          if ($artic_gral){
              $artic_gral->AG_STACT += $renglon->DF_CANTID;
              $artic_gral->AG_ULTENT = date('Y-m-d');
              $artic_gral->save();
          }
          //Si se fracciona en Quirofana la cantidad se multiplica por las unidades del envase
          if ($artic_gral->AG_FRACCQ=='S'){
            $cantidad_entrada = (isset($artic_gral->AG_UNIENV)) ? $renglon->DF_CANTID*$artic_gral->AG_UNIENV : $renglon->DF_CANTID;
          }else{
            $cantidad_entrada = $renglon->DF_CANTID;
          }

          $servicios_quirofano = Yii::$app->params['servicios_quirofano'];
          if (in_array($model->DE_SERSOL,$servicios_quirofano )){

            $movimiento_quirofano = Movimientos_quirofano::find()->where(['MO_CODART' => $renglon->DF_CODMON,
                                                        'MO_DEPOSITO' =>$renglon->DF_DEPOSITO,
                                                        'MO_TIPMOV' => "G",
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
                $movimiento_quirofano->MO_TIPMOV = "G";
                $movimiento_quirofano->MO_CANTIDA = $cantidad_entrada;
                $movimiento_quirofano->MO_CODART = $renglon->DF_CODMON;
                $movimiento_quirofano->MO_DEPOSITO = $renglon->DF_DEPOSITO;
               
            }
            
            if (!$movimiento_quirofano->save())
            {
              print_r($movimiento_quirofano->errors);
            }
          }
          else{
              
            $movimiento_sala = Movimientos_sala::find()->where(['MO_CODMON' => $renglon->DF_CODMON,
                                                          'MO_DEPOSITO' => $renglon->DF_DEPOSITO,
                                                          'MO_TIPMOV' => 'D',
                                                          'MO_FECHA' => $model->DE_FECHA,
                                                          'MO_CODSERV' => $model->DE_SERSOL,
                                                          'MO_HISCLI' => null,
                                                          ])->one();
            if ($movimiento_sala){
                $movimiento_sala->MO_CANT += $renglon->DF_CANTID;
            }
            else{
                $movimiento_sala = new Movimientos_sala();
                $movimiento_sala->MO_FECHA = $model->DE_FECHA;
                $movimiento_sala->MO_HORA = $model->DE_HORA;
                $movimiento_sala->MO_TIPMOV = 'D';
                $movimiento_sala->MO_CANT = $renglon->DF_CANTID;
                $movimiento_sala->MO_CODMON = $renglon->DF_CODMON;
                $movimiento_sala->MO_DEPOSITO = $renglon->DF_DEPOSITO;
                $movimiento_sala->MO_CODSERV =  $model->DE_SERSOL;
            }

            if (!$movimiento_sala->save())
            {
              print_r($movimiento_sala->errors);
            }
          }

          //Registramos el Movimiento Diario
          $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->DF_CODMON,
                                                      'MD_FECVEN' => $renglon->DF_FECVTO,
                                                      'MD_DEPOSITO' => $renglon->DF_DEPOSITO,
                                                      'MD_CODMOV' => "D",
                                                      'MD_FECHA' => $model->DE_FECHA,
                                                      ])->one();
                                     
       
          if ($movimiento){
              $movimiento->MD_CANT += $renglon->DF_CANTID;
          }
          else{
              $movimiento = new Movimientos_diarios();
              $movimiento->MD_FECHA = $model->DE_FECHA;
              $movimiento->MD_CODMOV = "D";
              $movimiento->MD_CANT = $renglon->DF_CANTID;
              $movimiento->MD_FECVEN =  $renglon->DF_FECVTO;
              $movimiento->MD_CODMON = $renglon->DF_CODMON;
              $movimiento->MD_DEPOSITO = $renglon->DF_DEPOSITO;
          }

          $movimiento->save();

          
       }

    }
    /**
     * Creates a new Devolucion_salas_granel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Devolucion_salas_granel();
        $model->scenario = "create";

        if ($model->load(Yii::$app->request->post())) {

             if ($model->validate() ){            
        
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                    
                    //Se guarda encabezado Devolucion
                     if ($model->save()){
                         $this->guardar_renglones($model);
                      }
                    $this->generarPdf($model->DE_NRODEVOL);
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->DE_NRODEVOL]);
    
                }
                catch (\Exception $e) {
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
     * Updates an existing Devolucion_salas_granel model.
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
     * Deletes an existing Devolucion_salas_granel model.
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
     * Finds the Devolucion_salas_granel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Devolucion_salas_granel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {   
        if (($model = Devolucion_salas_granel::findOne($id)) !== null) {
            $searchModel = new Devolucion_salas_granel_renglones();
            $model->renglones =  $searchModel->get_renglones($id);
            
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        
    }

      /*
    Obtiene la fecha de vencimiento mas antigua de un medicamento
    */
    public function actionVencimiento_codmon_remito()
    {

      $codmon = $_POST['codmon'];
      $deposito = $_POST['deposito'];
      $remito = $_POST['remito'];

      $query = Consumo_medicamentos_granel_renglones::find()->where(['PF_CODMON' => $codmon,
                                                             'PF_DEPOSITO' => $deposito,
                                                             'PF_NROREM' => $remito]);

      $vencimiento = $query->one(); 

       

       if ($vencimiento){
         $fecha_vto= Yii::$app->formatter->asDate($vencimiento->PF_FECVTO,'php:d-m-Y');
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

      $nombre = Yii::$app->params['local_path']['path_farmacia']."/devoluciones_granel/".$nombre;

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

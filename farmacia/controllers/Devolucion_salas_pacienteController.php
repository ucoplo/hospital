<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Devolucion_salas_paciente;
use farmacia\models\Devolucion_salas_pacienteSearch;
use farmacia\models\Devolucion_salas_paciente_renglones;
use farmacia\models\Consumo_medicamentos_pacientesSearch;
use farmacia\models\Consumo_medicamentos_pacientes;
use farmacia\models\Consumo_medicamentos_pacientes_renglones;
use farmacia\models\Vencimientos;
use farmacia\models\Movimientos_diarios;
use farmacia\models\Movimientos_sala;
use farmacia\models\ArticGral;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Devolucion_salas_pacienteController implements the CRUD actions for Devolucion_salas_paciente model.
 */
class Devolucion_salas_pacienteController extends Controller
{
    public $CodController="007";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::classname(),
                'only'=>['index','create','iniciar_creacion','seleccion_vale','view'],
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
     * Lists all Devolucion_salas_paciente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Devolucion_salas_pacienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSeleccion_vale()
    {
        $searchModel = new Consumo_medicamentos_pacientesSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false);

        return $this->render('seleccion_vale', [
            'dataProvider' => $dataProvider,
        ]);

        
    }
    /**
     * Displays a single Devolucion_salas_paciente model.
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
         $model = new Devolucion_salas_paciente();

         $vale_paciente = Consumo_medicamentos_pacientes::findOne($vale);

         $model->DE_SERSOL = $vale_paciente->CM_SERSOL;
         $model->DE_HISCLI = $vale_paciente->CM_HISCLI;
         $model->DE_DEPOSITO = $vale_paciente->CM_DEPOSITO;
         $model->DE_FECHA = date('Y-m-d');
         $model->DE_HORA = date('H:i:s');
         $model->DE_CODOPE = Yii::$app->user->identity->LE_NUMLEGA; //El usuario logueado
         $model->DE_ENFERM = $vale_paciente->CM_SUPERV;
         $model->DE_NUMVALOR = $vale_paciente->CM_NROVAL;
         $model->DE_UNIDIAG = $vale_paciente->CM_UNIDIAG;
         $model->DE_IDINTERNA = $vale_paciente->CM_IDINTERNA;        

         $model->renglones = [];

         return $this->render('create', [
                'model' => $model,
            ]);
    }

     private function guardar_renglones($model)
    {

     
       $num_renglon = 1;
       foreach ($model->renglones as $key => $obj) {
          $renglon = new Devolucion_salas_paciente_renglones();

          $renglon->DV_NRODEVOL = $model->DE_NRODEVOL;
          $renglon->DV_HISCLI = $model->DE_HISCLI;
          $renglon->DV_DEPOSITO = $model->DE_DEPOSITO;
          $renglon->DV_CODMON = $obj['DV_CODMON'];
          $renglon->DV_CANTID = $obj['DV_CANTID'];
          $renglon->DV_FECVTO =  date('Y-m-d', strtotime(str_replace("/","-",$obj['DV_FECVTO'])));
          $renglon->DV_NUMRENG = $num_renglon;
          
          //Se guarda cada renglón del Remito
          $renglon->save();
          $num_renglon++;                           

          //Incrementamos la cantidad en Vencimientos
          $vencimiento = Vencimientos::find()->where(['TV_CODART' => $renglon->DV_CODMON,
                                                      'TV_FECVEN' => $renglon->DV_FECVTO,
                                                      'TV_DEPOSITO' => $renglon->DV_DEPOSITO,
                                                      ])->one();

          if (count($vencimiento)==1){
              $vencimiento->TV_SALDO += $renglon->DV_CANTID;
              $vencimiento->save();

          }
      

          $movimiento_sala = Movimientos_sala::find()->where(['MO_CODMON' => $renglon->DV_CODMON,
                                                        'MO_DEPOSITO' => $renglon->DV_DEPOSITO,
                                                        'MO_TIPMOV' => 'U',
                                                        'MO_FECHA' => $model->DE_FECHA,
                                                        'MO_CODSERV' => $model->DE_SERSOL,
                                                        'MO_HISCLI' => $model->DE_HISCLI,
                                                        ])->one();
                                       
         
          if ($movimiento_sala){
              $movimiento_sala->MO_CANT += $renglon->DV_CANTID;
          }
          else{

              $movimiento_sala = new Movimientos_sala();
              $movimiento_sala->MO_FECHA = $model->DE_FECHA;
              $movimiento_sala->MO_HORA = $model->DE_HORA;
              $movimiento_sala->MO_TIPMOV = 'U';
              $movimiento_sala->MO_CANT = $renglon->DV_CANTID;
              $movimiento_sala->MO_CODMON = $renglon->DV_CODMON;
              $movimiento_sala->MO_DEPOSITO = $renglon->DV_DEPOSITO;
              $movimiento_sala->MO_CODSERV =  $model->DE_SERSOL;
              $movimiento_sala->MO_HISCLI =  $model->DE_HISCLI;

          }

          if (!$movimiento_sala->save())
          {
            print_r($movimiento_sala->errors);die();
          }

          //Registramos el Movimiento Diario
          $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->DV_CODMON,
                                                      'MD_FECVEN' => $renglon->DV_FECVTO,
                                                      'MD_DEPOSITO' => $renglon->DV_DEPOSITO,
                                                      'MD_CODMOV' => "D",
                                                      'MD_FECHA' => $model->DE_FECHA,
                                                      ])->one();
                                     
       
          if ($movimiento){
              $movimiento->MD_CANT += $renglon->DV_CANTID;
          }
          else{

              $movimiento = new Movimientos_diarios();
              $movimiento->MD_FECHA = $model->DE_FECHA;
              $movimiento->MD_CODMOV = "D";
              $movimiento->MD_CANT = $renglon->DV_CANTID;
              $movimiento->MD_FECVEN =  $renglon->DV_FECVTO;
              $movimiento->MD_CODMON = $renglon->DV_CODMON;
              $movimiento->MD_DEPOSITO = $renglon->DV_DEPOSITO;

          }

          $movimiento->save();

          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->DV_CODMON,
                                                      'AG_DEPOSITO' => $renglon->DV_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACT += $renglon->DV_CANTID;
              $artic_gral->AG_ULTENT = date('Y-m-d');
              $artic_gral->save();
          }
       }

    }
    /**
     * Creates a new Devolucion_salas_paciente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Devolucion_salas_paciente();
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
     * Updates an existing Devolucion_salas_paciente model.
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
     * Deletes an existing Devolucion_salas_paciente model.
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
     * Finds the Devolucion_salas_paciente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Devolucion_salas_paciente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {   
        if (($model = Devolucion_salas_paciente::findOne($id)) !== null) {
            $searchModel = new Devolucion_salas_paciente_renglones();
            $model->renglones =  $searchModel->get_renglones($id);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        
    }

    public function actionVencimiento_codmon_vale()
    {

      $codmon = $_POST['codmon'];
      $deposito = $_POST['deposito'];
      $vale = $_POST['vale'];

      $query = Consumo_medicamentos_pacientes_renglones::find()->where(['VA_CODMON' => $codmon,
                                                             'VA_DEPOSITO' => $deposito,
                                                             'VA_NROVALE' => $vale]);

      $vencimiento = $query->one(); 

       

       if ($vencimiento){
         $fecha_vto= Yii::$app->formatter->asDate($vencimiento->VA_FECVTO,'php:d-m-Y');
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

      $nombre = Yii::$app->params['local_path']['path_farmacia']."/devoluciones_pacientes/".$nombre;

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

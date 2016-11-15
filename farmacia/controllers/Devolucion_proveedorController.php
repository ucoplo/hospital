<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Devolucion_proveedor;
use farmacia\models\Devolucion_proveedorSearch;
use farmacia\models\Devolucion_proveedor_renglones;
use farmacia\models\Vencimientos;
use farmacia\models\Movimientos_diarios;
use farmacia\models\ArticGral;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
/**
 * Devolucion_proveedorController implements the CRUD actions for Devolucion_proveedor model.
 */
class Devolucion_proveedorController extends Controller
{
    public $CodController="003";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
        {
            return [
                'access'=>[
                    'class'=>AccessControl::classname(),
                    'only'=>['index','create','view',],

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
    public function actionValidateDevolucion() {
       
        $model= new Devolucion_proveedor();
        $model->scenario = "create";
        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
    /**
     * Lists all Devolucion_proveedor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Devolucion_proveedorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Devolucion_proveedor model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

     private function deshacer_renglones($model)
    {

       $renglones = Devolucion_proveedor_renglones::find()->where(['DP_NROREM' => $model->DE_NROREM])->all();

       
       foreach ($renglones as $key => $renglon) {
          //Incrementamos la cantidad en Vencimientos
          $vencimiento = Vencimientos::find()->where(['TV_CODART' => $renglon->DP_CODMON,
                                                      'TV_FECVEN' => $renglon->DP_FECVTO,
                                                      'TV_DEPOSITO' => $renglon->DP_DEPOSITO,
                                                      ])->one();

          if (count($vencimiento)==1){
              $vencimiento->TV_SALDO += $renglon->DP_CANTID;
              $vencimiento->save();

          }
          

          

          //Registramos el Movimiento Diario
          $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->DP_CODMON,
                                                      'MD_FECVEN' => $renglon->DP_FECVTO,
                                                      'MD_DEPOSITO' => $renglon->DP_DEPOSITO,
                                                      'MD_CODMOV' => "E",
                                                      'MD_FECHA' => $model->DE_FECHA,
                                                      ])->one();
                                     
       
          if ($movimiento){
              $movimiento->MD_CANT -= $renglon->DP_CANTID;
              if ($movimiento->MD_CANT <=0){
                $movimiento->delete();
              }
              else{
                $movimiento->save();      
              }

          }
          
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->DP_CODMON,
                                                      'AG_DEPOSITO' => $renglon->DP_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACT += $renglon->DP_CANTID;
              $artic_gral->AG_ULTSAL = date('Y-m-d');
              $artic_gral->save();
          }

          $renglon->delete();
          

       }

                             


    }

    private function guardar_renglones($model)
    {

     
       $num_renglon = 1;
       foreach ($model->renglones as $key => $obj) {
          $renglon = new Devolucion_Proveedor_renglones();

          $renglon->DP_NROREM = $model->DE_NROREM;
          $renglon->DP_DEPOSITO = $model->DE_DEPOSITO;
          $renglon->DP_NUMRENG = $num_renglon;
          $renglon->DP_CODMON = $obj['DP_CODMON'];
          //$renglon->RM_PRECIO = 0;
          $renglon->DP_CANTID = $obj['DP_CANTID'];
          $renglon->DP_FECVTO = $obj['DP_FECVTO'];
          
          //Se guarda cada renglón del Remito
          $renglon->save();
          $num_renglon++;                           

          //Decrementamos la cantidad en Vencimientos
          $vencimiento = Vencimientos::find()->where(['TV_CODART' => $renglon->DP_CODMON,
                                                      'TV_FECVEN' => $renglon->DP_FECVTO,
                                                      'TV_DEPOSITO' => $renglon->DP_DEPOSITO,
                                                      ])->one();

          if (count($vencimiento)==1){
              $vencimiento->TV_SALDO -= $renglon->DP_CANTID;
              $vencimiento->save();

          }
          

          

          //Registramos el Movimiento Diario
          $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->DP_CODMON,
                                                      'MD_FECVEN' => $renglon->DP_FECVTO,
                                                      'MD_DEPOSITO' => $renglon->DP_DEPOSITO,
                                                      'MD_CODMOV' => "D",
                                                      'MD_FECHA' => $model->DE_FECHA,
                                                      ])->one();
                                     
       
          if ($movimiento){
              $movimiento->MD_CANT += $renglon->DP_CANTID;
          }
          else{

              $movimiento = new Movimientos_diarios();
              $movimiento->MD_FECHA = $model->DE_FECHA;
              $movimiento->MD_CODMOV = "D";
              $movimiento->MD_CANT = $renglon->DP_CANTID;
              $movimiento->MD_FECVEN =  $renglon->DP_FECVTO;
              $movimiento->MD_CODMON = $renglon->DP_CODMON;
              $movimiento->MD_DEPOSITO = $renglon->DP_DEPOSITO;

          }

          $movimiento->save();

          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->DP_CODMON,
                                                      'AG_DEPOSITO' => $renglon->DP_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACT -= $renglon->DP_CANTID;
              $artic_gral->AG_ULTSAL = date('Y-m-d');
              $artic_gral->save();
          }
       }

                             


    }
    /**
     * Creates a new Devolucion_proveedor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Devolucion_proveedor();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {

             
            if ($model->validate() ){            
        
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                     $model->DE_CODOPE = Yii::$app->user->identity->LE_NUMLEGA;
                    
                    //Se guarda encabezado Remito
                     if ($model->save()){
                         $this->guardar_renglones($model);
                      }

                    
                    $this->generarPdf($model->DE_NROREM);
                    $transaction->commit();
                   return $this->redirect(['view', 'id' => $model->DE_NROREM]);

                
          
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

            $model->DE_FECHA = date('Y-m-d');
            $model->DE_HORA = date('H:i:s');
            $model->DE_DESTINO = 'E';

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Devolucion_proveedor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
         $model->scenario = 'update';

        $renglones = Devolucion_proveedor_renglones::find()->where(['DP_NROREM' => $id])->all();

        foreach ($renglones as $key => $renglon) {
          
          $renglon->descripcion = $renglon->dPCODMON->AG_NOMBRE;

          $query =  Vencimientos::find()->where(['TV_CODART' => $renglon->DP_CODMON,
                                                     'TV_DEPOSITO' => $model->DE_DEPOSITO,
                                                      ]);
          $query->andWhere([">", 'TV_SALDO', 0]);
        
         $renglon->vencimientos  = $query->all(); 
          

       
        }

        $model->renglones = $renglones;

        if ($model->load(Yii::$app->request->post())) {
             if ($model->validate() ){            
        
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                     $model->DE_CODOPE = Yii::$app->user->identity->LE_NUMLEGA;
                    
                    //Se guarda encabezado Remito
                     if ($model->save()){
                         $this->deshacer_renglones($model);
                         $this->guardar_renglones($model);
                      }

                    

                    $transaction->commit();
                   return $this->redirect(['view', 'id' => $model->DE_NROREM]);

                
          
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
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Devolucion_proveedor model.
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
     * Finds the Devolucion_proveedor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Devolucion_proveedor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    { 
        if (($model = Devolucion_proveedor::findOne($id)) !== null) {
            $searchModel = new Devolucion_proveedor_renglones();
            $model->renglones =  $searchModel->get_renglones($id);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionVencimientos_vigentes_select()
    {

      $codmon = $_POST['codmon'];
      $deposito = $_POST['deposito'];
       $query =  Vencimientos::find()->where(['TV_CODART' => $codmon,
                                                     'TV_DEPOSITO' => $deposito,
                                                   
                                                      ]);
       $query->andWhere([">", 'TV_SALDO', 0]);
        
       $vencimientos = $query->all(); 

       $result = array();
       $options = '';
       foreach($vencimientos as $item):

           $options.= "<option value='".$item->TV_FECVEN."'>".Yii::$app->formatter->asDate($item->TV_FECVEN,'php:d-m-Y')."</option>";
           
           $result[] = array(
               'id'   => $item->TV_FECVEN,
               'text' => $item->TV_FECVEN,
           );
        endforeach;

        //echo $options;
        return \yii\helpers\Json::encode( $options );
        //header('Content-type: application/json');
        //echo \yii\helpers\Json::encode( $result );
        //Yii::app()->end(); 

    }

    private function generarPdf($id){
      
      $model = $this->findModel($id);

      $content = $this->renderPartial('impresion', ['model' => $model]);
      
      $nombre = $id."_". date('d-m-Y')."_".date('Hi').".pdf";

      $nombre = Yii::$app->params['local_path']['path_farmacia']."/devoluciones_proveedor/".$nombre;

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
       
      $content = $this->renderPartial('impresion', ['model' => $model]);
      
      $pdf = new Pdf([
          'mode' => Pdf::MODE_UTF8,
          'format' => Pdf::FORMAT_A4, 
          'orientation' => Pdf::ORIENT_PORTRAIT, 
          'destination' => Pdf::DEST_BROWSER, 
          'content' => $content,//"<html><BODY><h1>prueba</h1></body></html>",  
          'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
          'cssInline' => '.kv-heading-1{font-size:18px}', 
           'options' => ['title' => 'Devolución a Proveedor'],
      ]);
     
      return $pdf->render(); 
    }
}

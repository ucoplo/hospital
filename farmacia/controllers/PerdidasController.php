<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Perdidas;
use farmacia\models\PerdidasSearch;
use farmacia\models\Perdidas_renglones;
use farmacia\models\Vencimientos;
use farmacia\models\Movimientos_diarios;
use farmacia\models\ArticGral;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
/**
 * PerdidasController implements the CRUD actions for Perdidas model.
 */
class PerdidasController extends Controller
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
                'only'=>['index','create','view','update','delete','report'],
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
     * Lists all Perdidas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PerdidasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Perdidas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

     private function guardar_renglones($model)
    {

     
       $num_renglon = 1;
       foreach ($model->renglones as $key => $obj) {
          $renglon = new Perdidas_renglones();

          $renglon->PF_NROREM = $model->PE_NROREM;
          $renglon->PF_DEPOSITO = $model->PE_DEPOSITO;
          $renglon->PF_CODMON = $obj['PF_CODMON'];
          $renglon->PF_CANTID = $obj['PF_CANTID'];
          $renglon->PF_FECVTO = $obj['PF_FECVTO'];
          
          //Se guarda cada renglón del Remito
          $renglon->save();
          $num_renglon++;                           

          //Decrementamos la cantidad en Vencimientos
          $vencimiento = Vencimientos::find()->where(['TV_CODART' => $renglon->PF_CODMON,
                                                      'TV_FECVEN' => $renglon->PF_FECVTO,
                                                      'TV_DEPOSITO' => $renglon->PF_DEPOSITO,
                                                      ])->one();

          if (count($vencimiento)==1){
              $vencimiento->TV_SALDO -= $renglon->PF_CANTID;
              $vencimiento->save();

          }
          

          

          //Registramos el Movimiento Diario
          $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->PF_CODMON,
                                                      'MD_FECVEN' => $renglon->PF_FECVTO,
                                                      'MD_DEPOSITO' => $renglon->PF_DEPOSITO,
                                                      'MD_CODMOV' => "D",
                                                      'MD_FECHA' => $model->PE_FECHA,
                                                      ])->one();
                                     
       
          if ($movimiento){
              $movimiento->MD_CANT += $renglon->PF_CANTID;
          }
          else{

              $movimiento = new Movimientos_diarios();
              $movimiento->MD_FECHA = $model->PE_FECHA;
              $movimiento->MD_CODMOV = "P";
              $movimiento->MD_CANT = $renglon->PF_CANTID;
              $movimiento->MD_FECVEN =  $renglon->PF_FECVTO;
              $movimiento->MD_CODMON = $renglon->PF_CODMON;
              $movimiento->MD_DEPOSITO = $renglon->PF_DEPOSITO;

          }

          $movimiento->save();

          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->PF_CODMON,
                                                      'AG_DEPOSITO' => $renglon->PF_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACT -= $renglon->PF_CANTID;
              $artic_gral->AG_ULTSAL = date('Y-m-d');
              $artic_gral->save();
          }
       }
    }
    /**
     * Creates a new Perdidas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Perdidas();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {

             
            if ($model->validate() ){            
        
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                     $model->PE_CODOPE = Yii::$app->user->identity->LE_NUMLEGA;
                    
                    //Se guarda encabezado Remito
                     if ($model->save()){
                         $this->guardar_renglones($model);
                      }

                    
                    $this->generarPdf($model->PE_NROREM);
                    $transaction->commit();
                   return $this->redirect(['view', 'id' => $model->PE_NROREM]);

                
          
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

            $model->PE_FECHA = date('Y-m-d');
            $model->PE_HORA = date('H:i:s');
            
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    
    }

    /**
     * Updates an existing Perdidas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->PE_NROREM]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Perdidas model.
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
     * Finds the Perdidas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Perdidas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
         if (($model = Perdidas::findOne($id)) !== null) {
            $searchModel = new Perdidas_renglones();
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
       
        return \yii\helpers\Json::encode( $options );
    }

     private function generarPdf($id){
      
      $model = $this->findModel($id);

      $content = $this->renderPartial('impresion', ['model' => $model]);
      
      $nombre = $id."_". date('d-m-Y')."_".date('Hi').".pdf";

      $nombre = Yii::$app->params['local_path']['path_farmacia']."/perdidas/".$nombre;

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
          'options' => ['title' => 'Pérdida'],
      ]);
      
      $pdf->render();

    }

    public function actionReport($id) {

      //header('Content-Type: application/pdf');
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
           'options' => ['title' => 'Pérdida'],
      ]);
     
      return $pdf->render(); 
    }
}

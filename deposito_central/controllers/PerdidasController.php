<?php

namespace deposito_central\controllers;

use Yii;
use deposito_central\models\Perdidas;
use deposito_central\models\PerdidasSearch;
use deposito_central\models\Perdidas_renglones;
use deposito_central\models\Vencimientos;
use deposito_central\models\Movimientos_diarios;
use deposito_central\models\ArticGral;

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

    public $CodController="016";
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
                        'actions'=>['index','create','view','report'],
                        'matchCallback' => 
                            function ($rule, $action) {
                                return Yii::$app->user->identity->habilitado($action);
                            }
                    ],
                    [
                        'allow'=>true,
                        'roles'=> ['@'],
                        'actions' => [],
                        
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

          $renglon->DR_NROREM = $model->DP_NROREM;
          $renglon->DR_DEPOSITO = $model->DP_DEPOSITO;
          $renglon->DR_CODART = $obj['DR_CODART'];
          $renglon->DR_CANTID = $obj['DR_CANTID'];
          $renglon->DR_FECVTO = $obj['DR_FECVTO'];
          
          //Se guarda cada renglón del Remito
          $renglon->save();
          $num_renglon++;                           

          //Decrementamos la cantidad en Vencimientos
          $vencimiento = Vencimientos::find()->where(['DT_CODART' => $renglon->DR_CODART,
                                                      'DT_FECVEN' => $renglon->DR_FECVTO,
                                                      'DT_DEPOSITO' => $renglon->DR_DEPOSITO,
                                                      ])->one();

          if (count($vencimiento)==1){
              $vencimiento->DT_SALDO -= $renglon->DR_CANTID;
              $vencimiento->save();

          }
          

          

          //Registramos el Movimiento Diario
          $movimiento = Movimientos_diarios::find()->where(['DM_CODART' => $renglon->DR_CODART,
                                                      'DM_FECVTO' => $renglon->DR_FECVTO,
                                                      'DM_DEPOSITO' => $renglon->DR_DEPOSITO,
                                                      'DM_CODMOV' => "D",
                                                      'DM_FECHA' => $model->DP_FECHA,
                                                      ])->one();
                                     
       
          if ($movimiento){
              $movimiento->DM_CANT += $renglon->DR_CANTID;
          }
          else{

              $movimiento = new Movimientos_diarios();
              $movimiento->DM_FECHA = $model->DP_FECHA;
              $movimiento->DM_CODMOV = "P";
              $movimiento->DM_CANT = $renglon->DR_CANTID;
              $movimiento->DM_FECVTO =  $renglon->DR_FECVTO;
              $movimiento->DM_CODART = $renglon->DR_CODART;
              $movimiento->DM_DEPOSITO = $renglon->DR_DEPOSITO;

          }

          $movimiento->save();

          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->DR_CODART,
                                                      'AG_DEPOSITO' => $renglon->DR_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACDEP -= $renglon->DR_CANTID;
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
                     $model->DP_CODOPE = Yii::$app->user->identity->LE_NUMLEGA;
                    
                    //Se guarda encabezado Remito
                     if ($model->save()){
                         $this->guardar_renglones($model);
                      }

                    
                    $this->generarPdf($model->DP_NROREM);
                    $transaction->commit();
                   return $this->redirect(['view', 'id' => $model->DP_NROREM]);

                
          
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

            $model->DP_FECHA = date('Y-m-d');
            $model->DP_HORA = date('H:i:s');
            
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
            return $this->redirect(['view', 'id' => $model->DP_NROREM]);
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

      $codart = $_POST['codart'];
      $deposito = $_POST['deposito'];
       $query =  Vencimientos::find()->where(['DT_CODART' => $codart,
                                                     'DT_DEPOSITO' => $deposito,
                                                   
                                                      ]);
       $query->andWhere([">", 'DT_SALDO', 0]);
        
       $vencimientos = $query->all(); 

       $result = array();
       $options = '';
       foreach($vencimientos as $item):

           $options.= "<option value='".$item->DT_FECVEN."'>".Yii::$app->formatter->asDate($item->DT_FECVEN,'php:d-m-Y')."</option>";
           
           $result[] = array(
               'id'   => $item->DT_FECVEN,
               'text' => $item->DT_FECVEN,
           );
        endforeach;
       
        return \yii\helpers\Json::encode( $options );
    }

     private function generarPdf($id){
      
      $model = $this->findModel($id);

      $content = $this->renderPartial('impresion', ['model' => $model]);
      
      $nombre = $id."_". date('d-m-Y')."_".date('Hi').".pdf";

      $nombre = Yii::$app->params['local_path']['path_deposito_central']."/perdidas/".$nombre;

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

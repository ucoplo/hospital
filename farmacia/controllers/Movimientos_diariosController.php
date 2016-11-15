<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Movimientos_diarios;
use farmacia\models\Movimientos_diarios_seleccion;
use farmacia\models\Movimientos_diariosSearch;
use farmacia\models\Vencimientos;
use farmacia\models\ArticGral;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * Movimientos_diariosController implements the CRUD actions for Movimientos_diarios model.
 */
class Movimientos_diariosController extends Controller
{
       public $CodController="010";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::classname(),
                'only'=>[],
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
     * Lists all Movimientos_diarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Movimientos_diariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Movimientos_diarios model.
     * @param string $MD_FECHA
     * @param string $MD_CODMOV
     * @param string $MD_FECVEN
     * @param string $MD_CODMON
     * @param string $MD_DEPOSITO
     * @return mixed
     */
    public function actionView($MD_FECHA, $MD_CODMOV, $MD_FECVEN, $MD_CODMON, $MD_DEPOSITO)
    {
        return $this->render('view', [
            'model' => $this->findModel($MD_FECHA, $MD_DEPOSITO),
        ]);
    }

    /**
     * Creates a new Movimientos_diarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Movimientos_diarios();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'MD_FECHA' => $model->MD_FECHA, 'MD_CODMOV' => $model->MD_CODMOV, 'MD_FECVEN' => $model->MD_FECVEN, 'MD_CODMON' => $model->MD_CODMON, 'MD_DEPOSITO' => $model->MD_DEPOSITO]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

     public function actionSeleccionar_movimientos()
    {
        $model = new Movimientos_diarios_seleccion();
        $model->renglones = 'renglones';
        if ($model->load(Yii::$app->request->post()) and $model->validate()) {
            return $this->redirect(['update', 'MD_FECHA' => $model->MD_FECHA,'MD_DEPOSITO' => $model->MD_DEPOSITO]);
        } else {
          
            return $this->render('seleccion_movimientos', [
                'model' => $model,
            ]);
        }
    }

    public function actionBlanquear_stock()
    {
        $model = new Movimientos_diarios_seleccion();
        $model->MD_FECHA = date('Y-m-d');
        $model->renglones = 'renglones';
        if ($model->load(Yii::$app->request->post()) and $model->validate()) {
            return $this->redirect(['blanquear', 'MD_FECHA' => $model->MD_FECHA,'MD_DEPOSITO' => $model->MD_DEPOSITO]);
        } else {
          
            return $this->render('seleccion_deposito_blanqueo', [
                'model' => $model,
            ]);
        }
    }

    private function guardar_renglones($model)
    {
       $query = Movimientos_diarios::find();
        $query->joinWith(['codigo']);
        $query->where(['MD_FECHA' => $model->MD_FECHA, 'MD_DEPOSITO' => $model->MD_DEPOSITO, 'MS_VALIDO'=> 1]);

        if ($model->MD_FECHA != date('Y-m-d')){
          $query->andFilterWhere(['>', 'MS_SIGNO',0]);
        }
        
        $renglones_antiguos = $query->all();
     

       foreach ($renglones_antiguos as $key => $renglon_antiguo) {
            $valor_antiguo = $renglon_antiguo->codigo->MS_SIGNO * $renglon_antiguo->MD_CANT;
          
          $vencimiento = Vencimientos::find()->where(['TV_CODART' => $renglon_antiguo->MD_CODMON,
                                                      'TV_FECVEN' => $renglon_antiguo->MD_FECVEN,
                                                      'TV_DEPOSITO' => $renglon_antiguo->MD_DEPOSITO,
                                                      ])->one();

          if (count($vencimiento)==1){
              $vencimiento->TV_SALDO = $vencimiento->TV_SALDO - $valor_antiguo;
              $vencimiento->save();

          }
       
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon_antiguo->MD_CODMON,
                                                      'AG_DEPOSITO' => $renglon_antiguo->MD_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACT =  $artic_gral->AG_STACT - $valor_antiguo;
              $artic_gral->save();
          }

          $renglon_antiguo->delete();
       }

       $num_renglon = 1;
       foreach ($model->renglones as $key => $obj) {
          $renglon = new Movimientos_diarios();

          $renglon->MD_FECHA = $model->MD_FECHA;
          $renglon->MD_DEPOSITO = $model->MD_DEPOSITO;
          $renglon->MD_CODMON = $obj['MD_CODMON'];
          $renglon->MD_CANT = $obj['MD_CANT'];
          $renglon->MD_FECVEN = $obj['MD_FECVEN'];
          $renglon->MD_CODMOV = $obj['MD_CODMOV'];

          //Se guarda cada renglón del Remito
          if (!$renglon->save()){
            print_r($renglon->errors);die();
          }
          $num_renglon++;                           

          $valor = $renglon->codigo->MS_SIGNO * $renglon->MD_CANT;
       
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->MD_CODMON,
                                                      'AG_DEPOSITO' => $renglon->MD_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACT =  $artic_gral->AG_STACT + $valor;
              $artic_gral->save();
              
              $vencimiento = Vencimientos::find()->where(['TV_CODART' => $renglon->MD_CODMON,
                                                      'TV_FECVEN' => $renglon->MD_FECVEN,
                                                      'TV_DEPOSITO' => $renglon->MD_DEPOSITO,
                                                      ])->one();

              if (count($vencimiento)==1){
                $vencimiento->TV_SALDO = $vencimiento->TV_SALDO + $valor;
                $vencimiento->save();
              }
              elseif ($renglon->codigo->MS_SIGNO>=0){
                $vencimiento = new Vencimientos();
                $vencimiento->TV_CODART = $renglon->MD_CODMON;
                $vencimiento->TV_FECVEN = $renglon->MD_FECVEN;
                $vencimiento->TV_SALDO = $valor;
                $vencimiento->TV_DEPOSITO = $renglon->MD_DEPOSITO;
                $vencimiento->save();
              }

          }
       }
    }
    /**
     * Updates an existing Movimientos_diarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $MD_FECHA
     * @param string $MD_CODMOV
     * @param string $MD_FECVEN
     * @param string $MD_CODMON
     * @param string $MD_DEPOSITO
     * @return mixed
     */
    public function actionUpdate($MD_FECHA, $MD_DEPOSITO)
    {
        $model = $this->findModel($MD_FECHA, $MD_DEPOSITO);
        $model->scenario = "update";

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            //echo "<pre>";print_r($model);echo "</pre>";die();
            try {
                
                $this->guardar_renglones($model);
                
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Movimientos Guardados con éxito.');
                return $this->redirect(['seleccionar_movimientos']);
            }
            catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }

            return $this->redirect(['seleccionar_movimientos']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    private function blanquear_medicamentos($model)
    {
      foreach ($model->renglones as $key => $obj) {
        
       
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' =>  $obj['MD_CODMON'],
                                                      'AG_DEPOSITO' => $model->MD_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACT =  0;
              $artic_gral->save();
              
              $vencimientos = Vencimientos::find()->where(['TV_CODART' => $obj['MD_CODMON'],
                                                          'TV_DEPOSITO' => $model->MD_DEPOSITO,
                                                      ])->all();

              foreach ($vencimientos as $key => $venc){
                  $cantidad = $venc->TV_SALDO;
                  if ($cantidad!=0){
                    if ($cantidad>0)
                        $tipo_movimiento = 'Y';
                      else
                        $tipo_movimiento = 'Z';

                    $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $venc->TV_CODART,
                                                      'MD_FECVEN' => $venc->TV_FECVEN,
                                                      'MD_DEPOSITO' => $model->MD_DEPOSITO,
                                                      'MD_CODMOV' => $tipo_movimiento,
                                                      'MD_FECHA' => $model->MD_FECHA,
                                                      ])->one();
                                     
       
                    if ($movimiento){
                      $movimiento->MD_CANT += abs($cantidad);
                    }
                    else{

                      $movimiento = new Movimientos_diarios();

                      $movimiento->MD_FECHA = $model->MD_FECHA;
                      $movimiento->MD_DEPOSITO = $model->MD_DEPOSITO;
                      $movimiento->MD_CODMON = $venc->TV_CODART;
                      $movimiento->MD_CANT = abs($cantidad);
                      $movimiento->MD_FECVEN = $venc->TV_FECVEN;
                      $movimiento->MD_CODMOV = $tipo_movimiento;
                    }
                    $movimiento->save();
                  }
                  $venc->TV_SALDO = 0;
                  $venc->save();
              }
          }
      }
    }
    public function actionBlanquear($MD_FECHA, $MD_DEPOSITO)
    {
        $model =  new Movimientos_diarios_seleccion();
        $model->MD_FECHA = $MD_FECHA;
        $model->MD_DEPOSITO = $MD_DEPOSITO;    
        $model->scenario = "blanquear";

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            
            try {
                
                $this->blanquear_medicamentos($model);
                
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Blanqueo de Stock realizado con éxito.');
                return $this->redirect(['blanquear_stock']);
            }
            catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            return $this->render('blanqueo', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Movimientos_diarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $MD_FECHA
     * @param string $MD_CODMOV
     * @param string $MD_FECVEN
     * @param string $MD_CODMON
     * @param string $MD_DEPOSITO
     * @return mixed
     */
    public function actionDelete($MD_FECHA, $MD_CODMOV, $MD_FECVEN, $MD_CODMON, $MD_DEPOSITO)
    {
        $this->findModel($MD_FECHA, $MD_DEPOSITO)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Movimientos_diarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $MD_FECHA
     * @param string $MD_CODMOV
     * @param string $MD_FECVEN
     * @param string $MD_CODMON
     * @param string $MD_DEPOSITO
     * @return Movimientos_diarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($MD_FECHA, $MD_DEPOSITO)
    {
        $model =  new Movimientos_diarios_seleccion();
        $model->MD_FECHA = $MD_FECHA;
        $model->MD_DEPOSITO = $MD_DEPOSITO;     
        
        $query = Movimientos_diarios::find();
        $query->joinWith(['codigo']);
        $query->where(['MD_FECHA' => $MD_FECHA, 'MD_DEPOSITO' => $MD_DEPOSITO, 'MS_VALIDO'=> 1]);

        if ($MD_FECHA != date('Y-m-d')){
          $query->andFilterWhere(['>', 'MS_SIGNO',0]);
        }
        
        $renglones = $query->all();

        foreach ($renglones as $key => $renglon) {
          $renglones[$key]->descripcion = $renglon->monodroga->AG_NOMBRE;
          $renglon->MD_FECVEN = Yii::$app->formatter->asDate($renglon->MD_FECVEN,'php:d-m-Y');
        }

        $model->renglones = $renglones;

        return $model;
        
    }
}

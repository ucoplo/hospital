<?php

namespace deposito_central\controllers;

use Yii;
use deposito_central\models\Movimientos_diarios;
use deposito_central\models\Movimientos_diarios_seleccion;
use deposito_central\models\Movimientos_diariosSearch;
use deposito_central\models\Vencimientos;
use deposito_central\models\ArticGral;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * Movimientos_diariosController implements the CRUD actions for Movimientos_diarios model.
 */
class Movimientos_diariosController extends Controller
{
       public $CodController="017";
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
                        'actions'=>['seleccionar_movimientos','update','blanquear_stock','blanquear'],
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
     * @param string $DM_FECHA
     * @param string $DM_CODMOV
     * @param string $DM_FECVTO
     * @param string $DM_CODART
     * @param string $DM_DEPOSITO
     * @return mixed
     */
    public function actionView($DM_FECHA, $DM_CODMOV, $DM_FECVTO, $DM_CODART, $DM_DEPOSITO)
    {
        return $this->render('view', [
            'model' => $this->findModel($DM_FECHA, $DM_DEPOSITO),
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
            return $this->redirect(['view', 'DM_FECHA' => $model->DM_FECHA, 'DM_CODMOV' => $model->DM_CODMOV, 'DM_FECVTO' => $model->DM_FECVTO, 'DM_CODART' => $model->DM_CODART, 'DM_DEPOSITO' => $model->DM_DEPOSITO]);
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
            return $this->redirect(['update', 'DM_FECHA' => $model->DM_FECHA,'DM_DEPOSITO' => $model->DM_DEPOSITO]);
        } else {
          
            return $this->render('seleccion_movimientos', [
                'model' => $model,
            ]);
        }
    }

    public function actionBlanquear_stock()
    {
        $model = new Movimientos_diarios_seleccion();
        $model->DM_FECHA = date('Y-m-d');
        $model->renglones = 'renglones';
        if ($model->load(Yii::$app->request->post()) and $model->validate()) {
            return $this->redirect(['blanquear', 'DM_FECHA' => $model->DM_FECHA,'DM_DEPOSITO' => $model->DM_DEPOSITO]);
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
        $query->where(['DM_FECHA' => $model->DM_FECHA, 'DM_DEPOSITO' => $model->DM_DEPOSITO, 'DM_VALIDO'=> 1]);

        if ($model->DM_FECHA != date('Y-m-d')){
          $query->andFilterWhere(['>', 'DM_SIGNO',0]);
        }
        
        $renglones_antiguos = $query->all();
     

       foreach ($renglones_antiguos as $key => $renglon_antiguo) {
            $valor_antiguo = $renglon_antiguo->codigo->DM_SIGNO * $renglon_antiguo->DM_CANT;
          
          $vencimiento = Vencimientos::find()->where(['DT_CODART' => $renglon_antiguo->DM_CODART,
                                                      'DT_FECVEN' => $renglon_antiguo->DM_FECVTO,
                                                      'DT_DEPOSITO' => $renglon_antiguo->DM_DEPOSITO,
                                                      ])->one();

          if (count($vencimiento)==1){
              $vencimiento->DT_SALDO = $vencimiento->DT_SALDO - $valor_antiguo;
              $vencimiento->save();

          }
       
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon_antiguo->DM_CODART,
                                                      'AG_DEPOSITO' => $renglon_antiguo->DM_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACDEP =  $artic_gral->AG_STACDEP - $valor_antiguo;
              $artic_gral->save();
          }

          $renglon_antiguo->delete();
       }

       $num_renglon = 1;
       foreach ($model->renglones as $key => $obj) {
          $renglon = new Movimientos_diarios();

          $renglon->DM_FECHA = $model->DM_FECHA;
          $renglon->DM_DEPOSITO = $model->DM_DEPOSITO;
          $renglon->DM_CODART = $obj['DM_CODART'];
          $renglon->DM_CANT = $obj['DM_CANT'];
          $renglon->DM_FECVTO = $obj['DM_FECVTO'];
          $renglon->DM_CODMOV = $obj['DM_CODMOV'];

          //Se guarda cada renglón del Remito
          if (!$renglon->save()){
            print_r($renglon->errors);die();
          }
          $num_renglon++;                           

          $valor = $renglon->codigo->DM_SIGNO * $renglon->DM_CANT;
       
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->DM_CODART,
                                                      'AG_DEPOSITO' => $renglon->DM_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACDEP =  $artic_gral->AG_STACDEP + $valor;
              $artic_gral->save();
              
              $vencimiento = Vencimientos::find()->where(['DT_CODART' => $renglon->DM_CODART,
                                                      'DT_FECVEN' => $renglon->DM_FECVTO,
                                                      'DT_DEPOSITO' => $renglon->DM_DEPOSITO,
                                                      ])->one();

              if (count($vencimiento)==1){
                $vencimiento->DT_SALDO = $vencimiento->DT_SALDO + $valor;
                $vencimiento->save();
              }
              elseif ($renglon->codigo->DM_SIGNO>=0){
                $vencimiento = new Vencimientos();
                $vencimiento->DT_CODART = $renglon->DM_CODART;
                $vencimiento->DT_FECVEN = $renglon->DM_FECVTO;
                $vencimiento->DT_SALDO = $valor;
                $vencimiento->DT_DEPOSITO = $renglon->DM_DEPOSITO;
                $vencimiento->save();
              }

          }
       }
    }
    /**
     * Updates an existing Movimientos_diarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $DM_FECHA
     * @param string $DM_CODMOV
     * @param string $DM_FECVTO
     * @param string $DM_CODART
     * @param string $DM_DEPOSITO
     * @return mixed
     */
    public function actionUpdate($DM_FECHA, $DM_DEPOSITO)
    {
        $model = $this->findModel($DM_FECHA, $DM_DEPOSITO);
        $model->scenario = "update";

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            //echo "<pre>";print_r($model);echo "</pre>";die();
            try {
                
                $this->guardar_renglones($model);
                
                $transaction->commit();
                Yii::$app->session->setFlash('exito_deposito_central', 'Movimientos Guardados con éxito.');
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
        
       
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' =>  $obj['DM_CODART'],
                                                      'AG_DEPOSITO' => $model->DM_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACDEP =  0;
              $artic_gral->save();
              
              $vencimientos = Vencimientos::find()->where(['DT_CODART' => $obj['DM_CODART'],
                                                          'DT_DEPOSITO' => $model->DM_DEPOSITO,
                                                      ])->all();

              foreach ($vencimientos as $key => $venc){
                  $cantidad = $venc->DT_SALDO;
                  if ($cantidad!=0){
                    if ($cantidad>0)
                        $tipo_movimiento = 'Y';
                      else
                        $tipo_movimiento = 'Z';

                    $movimiento = Movimientos_diarios::find()->where(['DM_CODART' => $venc->DT_CODART,
                                                      'DM_FECVTO' => $venc->DT_FECVEN,
                                                      'DM_DEPOSITO' => $model->DM_DEPOSITO,
                                                      'DM_CODMOV' => $tipo_movimiento,
                                                      'DM_FECHA' => $model->DM_FECHA,
                                                      ])->one();
                                     
       
                    if ($movimiento){
                      $movimiento->DM_CANT += abs($cantidad);
                    }
                    else{

                      $movimiento = new Movimientos_diarios();

                      $movimiento->DM_FECHA = $model->DM_FECHA;
                      $movimiento->DM_DEPOSITO = $model->DM_DEPOSITO;
                      $movimiento->DM_CODART = $venc->DT_CODART;
                      $movimiento->DM_CANT = abs($cantidad);
                      $movimiento->DM_FECVTO = $venc->DT_FECVEN;
                      $movimiento->DM_CODMOV = $tipo_movimiento;
                    }
                    $movimiento->save();
                  }
                  $venc->DT_SALDO = 0;
                  $venc->save();
              }
          }
      }
    }
    public function actionBlanquear($DM_FECHA, $DM_DEPOSITO)
    {
        $model =  new Movimientos_diarios_seleccion();
        $model->DM_FECHA = $DM_FECHA;
        $model->DM_DEPOSITO = $DM_DEPOSITO;    
        $model->scenario = "blanquear";

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            
            try {
                
                $this->blanquear_medicamentos($model);
                
                $transaction->commit();
                Yii::$app->session->setFlash('exito_deposito_central', 'Blanqueo de Stock realizado con éxito.');
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
     * @param string $DM_FECHA
     * @param string $DM_CODMOV
     * @param string $DM_FECVTO
     * @param string $DM_CODART
     * @param string $DM_DEPOSITO
     * @return mixed
     */
    public function actionDelete($DM_FECHA, $DM_CODMOV, $DM_FECVTO, $DM_CODART, $DM_DEPOSITO)
    {
        $this->findModel($DM_FECHA, $DM_DEPOSITO)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Movimientos_diarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $DM_FECHA
     * @param string $DM_CODMOV
     * @param string $DM_FECVTO
     * @param string $DM_CODART
     * @param string $DM_DEPOSITO
     * @return Movimientos_diarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($DM_FECHA, $DM_DEPOSITO)
    {
        $model =  new Movimientos_diarios_seleccion();
        $model->DM_FECHA = $DM_FECHA;
        $model->DM_DEPOSITO = $DM_DEPOSITO;     
        
        $query = Movimientos_diarios::find();
        $query->joinWith(['codigo']);
        $query->where(['DM_FECHA' => $DM_FECHA, 'DM_DEPOSITO' => $DM_DEPOSITO, 'DM_VALIDO'=> 1]);

        if ($DM_FECHA != date('Y-m-d')){
          $query->andFilterWhere(['>', 'DM_SIGNO',0]);
        }
        
        $renglones = $query->all();

        foreach ($renglones as $key => $renglon) {
          $renglones[$key]->descripcion = $renglon->articulo->AG_NOMBRE;
          $renglon->DM_FECVTO = Yii::$app->formatter->asDate($renglon->DM_FECVTO,'php:d-m-Y');
        }

        $model->renglones = $renglones;

        return $model;
        
    }
}

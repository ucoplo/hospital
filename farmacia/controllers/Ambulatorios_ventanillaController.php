<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Ambulatorios_ventanilla;
use farmacia\models\Ambulatorios_ventanillaSearch;
use farmacia\models\Ambulatorios_ventanilla_renglones;
use farmacia\models\Vencimientos;
use farmacia\models\Movimientos_diarios;
use farmacia\models\ArticGral;
use farmacia\models\Paciente;
use farmacia\models\Receta_electronicaSearch;
use farmacia\models\Receta_electronica_renglones;
use farmacia\models\Receta_electronica_renglonesSearch;
use farmacia\models\Programa_medicamentos;
use kartik\mpdf\Pdf;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

/**
 * Ambulatorios_ventanillaController implements the CRUD actions for Ambulatorios_ventanilla model.
 */
class Ambulatorios_ventanillaController extends Controller
{
    public $CodController="025";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::classname(),
                'only'=>['index','create','view','update','delete'],
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
     * Lists all Ambulatorios_ventanilla models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Ambulatorios_ventanillaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ambulatorios_ventanilla model.
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

       $renglones = Ambulatorios_ventanilla_renglones::find()->where(['AM_NUMVALE' => $model->AM_NUMVALE])->all();

       
       foreach ($renglones as $key => $renglon) {
          //Incrementamos la cantidad en Vencimientos
          $vencimiento = Vencimientos::find()->where(['TV_CODART' => $renglon->AM_CODMON,
                                                      'TV_FECVEN' => $renglon->AM_FECVTO,
                                                      'TV_DEPOSITO' => $renglon->AM_DEPOSITO,
                                                      ])->one();

          if (count($vencimiento)==1){
              $vencimiento->TV_SALDO += $renglon->AM_CANTENT;
              $vencimiento->save();

          }
          

          

          //Registramos el Movimiento Diario
          $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->AM_CODMON,
                                                      'MD_FECVEN' => $renglon->AM_FECVTO,
                                                      'MD_DEPOSITO' => $renglon->AM_DEPOSITO,
                                                      'MD_CODMOV' => "V",
                                                      'MD_FECHA' => $model->AM_FECHA,
                                                      ])->one();
                                     
       
          if ($movimiento){
              $movimiento->MD_CANT -= $renglon->AM_CANTENT;
              if ($movimiento->MD_CANT <=0){
                $movimiento->delete();
              }
              else{
                $movimiento->save();      
              }

          }
          
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->AM_CODMON,
                                                      'AG_DEPOSITO' => $renglon->AM_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACT += $renglon->AM_CANTENT;
              //Debería buscarse el ultimo vale real
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
    
          //Decrementamos la cantidad en Vencimientos
          $cantidad_entregada = $obj['AM_CANTENT'];
          $cantidad_pedida = $obj['AM_CANTPED'];
          $cantidad_entrega_renglon = 0;
          $cantidad_pedida_renglon = 0;
          while ( $cantidad_entregada > 0) {
              $vencimiento_vigente = $this->vencimiento_vigente($obj['AM_CODMON'],$model->AM_DEPOSITO);

              if ($vencimiento_vigente){
                if ($vencimiento_vigente->TV_SALDO < $cantidad_entregada){
                  $cantidad_entregada -= $vencimiento_vigente->TV_SALDO;
                  $cantidad_renglon = $vencimiento_vigente->TV_SALDO;
                  $cantidad_pedida_renglon = $cantidad_renglon;
                  $cantidad_pedida -= $cantidad_pedida_renglon;
                  $vencimiento_vigente->TV_SALDO = 0;
                }else{
                  $vencimiento_vigente->TV_SALDO -= $cantidad_entregada;
                  $cantidad_renglon = $cantidad_entregada;
                  $cantidad_pedida_renglon = $cantidad_pedida;
                  $cantidad_entregada =0;
                }
                $vencimiento_vigente->save();
                //Se guarda cada renglón del Vale
                $renglon = new Ambulatorios_ventanilla_renglones();

                $renglon->AM_NUMVALE = $model->AM_NUMVALE;
                $renglon->AM_DEPOSITO = $model->AM_DEPOSITO;
                $renglon->AM_CODMON = $obj['AM_CODMON'];
                //$renglon->RM_PRECIO = 0;
                $renglon->AM_CANTPED = $cantidad_pedida_renglon;
                $renglon->AM_NUMREN = $num_renglon;
                $renglon->AM_FECVTO =  $vencimiento_vigente->TV_FECVEN;
                $renglon->AM_CANTENT = $cantidad_renglon;
                $renglon->save();
                $num_renglon++;   
                                      
              }

              //Registramos el Movimiento Diario
              $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->AM_CODMON,
                                                          'MD_FECVEN' => $renglon->AM_FECVTO,
                                                          'MD_DEPOSITO' => $renglon->AM_DEPOSITO,
                                                          'MD_CODMOV' => "V",
                                                          'MD_FECHA' => $model->AM_FECHA,
                                                          ])->one();
                                         
           
              if ($movimiento){
                  $movimiento->MD_CANT += $renglon->AM_CANTENT;
              }
              else{

                  $movimiento = new Movimientos_diarios();
                  $movimiento->MD_FECHA = $model->AM_FECHA;
                  $movimiento->MD_CODMOV = "V";
                  $movimiento->MD_CANT = $renglon->AM_CANTENT;
                  $movimiento->MD_FECVEN =  $renglon->AM_FECVTO;
                  $movimiento->MD_CODMON = $renglon->AM_CODMON;
                  $movimiento->MD_DEPOSITO = $renglon->AM_DEPOSITO;

              }

              $movimiento->save();
          }

          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->AM_CODMON,
                                                      'AG_DEPOSITO' => $renglon->AM_DEPOSITO,
                                                      ])->one();

          if ($artic_gral){
              $artic_gral->AG_STACT -= $obj['AM_CANTENT'];
              $artic_gral->AG_ULTSAL = date('Y-m-d');
              $artic_gral->save();
          }
       }

                             


    }


    /**
     * Creates a new Ambulatorios_ventanilla model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ambulatorios_ventanilla();
        $model->scenario = 'create';
        $paciente = new Paciente();

        if ($model->load(Yii::$app->request->post())) {
            $paciente->load(Yii::$app->request->post());
            if ($model->validate() ){            
        
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                    //Se guarda encabezado del Vale
                     $model->AM_FARMACEUTICO = Yii::$app->user->identity->LE_NUMLEGA;
                     if ($model->save()){
                        $this->guardar_renglones($model);
                      }
                    $this->generarPdf($model->AM_NUMVALE);
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->AM_NUMVALE]);
                }
                catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
            else{
               return $this->render('create', [
                  'model' => $model,
                  'paciente' => $paciente,
              ]);
            }
        } else {
            $model->AM_FECHA = date('Y-m-d');
            $model->AM_HORA = date('H:i:s');
            $model->AM_DEPOSITO = '03';

            if (isset($_GET['hiscli'])){
                $paciente = Paciente::findOne(['PA_HISCLI' => $_GET['hiscli']]);
                $paciente->edad = $this->edad($paciente->PA_FECNAC);
                $model->AM_HISCLI = $paciente->PA_HISCLI;
            }

             return $this->render('create', [
                'model' => $model,
                'paciente' => $paciente
            ]);
        }
    }

    private function cantidad_retiros_x_medicamento($hiscli,$codmon){
        $query = (new \yii\db\Query())
        ->select(['SUM(AM_CANTENT) as cantidad_acumulada'])
        ->from('ambu_enc')
        ->join('INNER JOIN', 'ambu_ren', 'ambu_ren.AM_NUMVALE = ambu_enc.AM_NUMVALE')
        ->join('INNER JOIN', 'artic_gral', 'artic_gral.AG_CODIGO = ambu_ren.AM_CODMON')
        ->where(['AM_HISCLI' => $hiscli]);
        

        $dt_vigencia= date('Y-m-d', strtotime('-30 day')) ; // resta 30 días
        $query->andFilterWhere(['>=', 'AM_FECHA', $dt_vigencia]);
        $query->andFilterWhere(['AM_CODMON' => $codmon]);

        $cantidad = $query->one();
        if (isset($cantidad['cantidad_acumulada'])){
          return $cantidad['cantidad_acumulada'];
        }else{
          return 0;
        }
         
    }

    private function edad( $fecha ) {
      if (isset($fecha)){
        list($Y,$m,$d) = explode("-",$fecha);
        return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
      }
      else{
        return '';
      }
    }

    private function agrupar_medicamentos($renglones){
      
      $codigos_medicamentos=[];
      $codmon = '';
      $cant_renglones = count($renglones);
      $nuevos_renglones = [];

      $sum_cant_ent = 0;
      $sum_cant_ped = 0;

      for ($i=0; $i < $cant_renglones; $i++) { 
           
        $renglon = $renglones[$i];



        if ($renglon->AM_CODMON!=$codmon){
          
          if ($codmon != ''){

            $renglon_nuevo->AM_CANTENT = number_format($sum_cant_ent, 2, '.', '');
            $renglon_nuevo->AM_CANTPED = number_format($sum_cant_ped, 2, '.', '');
            $nuevos_renglones[] = $renglon_nuevo;

            $renglon_nuevo = $renglon;
            $codmon = $renglon_nuevo->AM_CODMON;
            $sum_cant_ent = $renglon->AM_CANTENT;
            $sum_cant_ped = $renglon->AM_CANTPED; 

            
          }else{
            $renglon_nuevo = $renglon;
            $codmon = $renglon_nuevo->AM_CODMON;
            $sum_cant_ent += $renglon->AM_CANTENT;
            $sum_cant_ped += $renglon->AM_CANTPED; 
          }
          if (($i+1)==$cant_renglones){
              $nuevos_renglones[] = $renglon_nuevo;              
            }
        }else{
          
           $sum_cant_ent += $renglon->AM_CANTENT;
           $sum_cant_ped += $renglon->AM_CANTPED; 

           if (($i+1)==$cant_renglones){
              $renglon_nuevo->AM_CANTENT = number_format($sum_cant_ent, 2, '.', '');
            $renglon_nuevo->AM_CANTPED = number_format($sum_cant_ped, 2, '.', '');
              $nuevos_renglones[] = $renglon_nuevo;            
           }
          
        }

      }

      return $nuevos_renglones;
    }
    /**
     * Updates an existing Ambulatorios_ventanilla model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $renglones = Ambulatorios_ventanilla_renglones::find()->where(['AM_NUMVALE' => $id])->all();

        $renglones = $this->agrupar_medicamentos($renglones);
        
        foreach ($renglones as $key => $renglon) {
          $renglones[$key]->descripcion = $renglon->monodroga->AG_NOMBRE;
          $renglon->cant_acumulada = $this->cantidad_retiros_x_medicamento($model->AM_HISCLI,$renglon->AM_CODMON);
          $renglon->AM_FECVTO = Yii::$app->formatter->asDate($renglon->AM_FECVTO,'php:d-m-Y');
        }

        $model->renglones = $renglones;

        $paciente = Paciente::findOne(['PA_HISCLI' => $model->AM_HISCLI]);

        $paciente->edad = $this->edad($paciente->PA_FECNAC);


          if ($model->load(Yii::$app->request->post()))  {
               $paciente->load(Yii::$app->request->post());
               if ($model->validate() ){            
          
                  $connection = \Yii::$app->db;
                  $transaction = $connection->beginTransaction();

                  try {
                      //Se guarda encabezado Remito
                       if ($model->save()){
                           $this->deshacer_renglones($model);
                           $this->guardar_renglones($model);
                        }
                      $transaction->commit();
                     return $this->redirect(['view', 'id' => $model->AM_NUMVALE]);
                  }
                  catch (\Exception $e) {
                      $transaction->rollBack();
                      throw $e;
                  }
              }
              else{
                 return $this->render('update', [
                    'model' => $model,
                    'paciente' => $paciente,
                ]);
              }
          } else {

              return $this->render('update', [
                  'model' => $model,
                  'paciente' => $paciente,
              ]);
          }

    }

    /**
     * Deletes an existing Ambulatorios_ventanilla model.
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

    public function actionHistorialretiros($AM_HISCLI=0)
    {
        $date = new \yii\db\Expression("DATE_FORMAT(`AM_FECHA`, '%d-%m-%Y')");
        

      $query = (new \yii\db\Query())
        ->select(["$date as fecha",'AM_HORA','AM_CODMON','AG_NOMBRE','AM_CANTENT','AM_CANTPED'])
        ->from('ambu_enc')
        ->join('INNER JOIN', 'ambu_ren', 'ambu_ren.AM_NUMVALE = ambu_enc.AM_NUMVALE')
        ->join('INNER JOIN', 'artic_gral', 'artic_gral.AG_CODIGO = ambu_ren.AM_CODMON')
        ->where(['AM_HISCLI' => $AM_HISCLI])
        ->orderBy(['AM_FECHA' => SORT_DESC]);

      $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

     
      return $this->renderAjax('historialpaciente', [
                'dataProvider' => $dataProvider,
            ]);
    }

    public function actionRecetaspaciente($AM_HISCLI=0)
    {
        $searchModel = new Receta_electronicaSearch();
        $params[$searchModel->formName()]['RE_HISCLI'] = $AM_HISCLI; 
        $dataProvider = $searchModel->recetas_vigentes($params);

        return $this->renderAjax('recetaspaciente', [
                'dataProvider' => $dataProvider,
            ]);
    }
    /**
     * Finds the Ambulatorios_ventanilla model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ambulatorios_ventanilla the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ambulatorios_ventanilla::findOne($id)) !== null) {
            $searchModel = new Ambulatorios_ventanilla_renglones();
            $model->renglones =  $searchModel->get_renglones($id);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    private function vencimiento_vigente($codmon,$deposito){

       $query =  Vencimientos::find()->where(['TV_CODART' => $codmon,
                                                     'TV_DEPOSITO' => $deposito,
                                                   
                                                      ]);

       
       $query->andWhere([">", 'TV_SALDO', 0]);

       $query->orderBy(['TV_FECVEN'=>SORT_ASC]);


       $vencimientos = $query->one(); 

       
       return $vencimientos;

    }
    /*
    Obtiene la fecha de vencimiento mas antigua de un medicamento
    */
    public function actionVencimiento_vigente_codmon()
    {

      $codmon = $_POST['codmon'];
      $deposito = $_POST['deposito'];

       $vencimiento = $this->vencimiento_vigente($codmon,$deposito);

       if ($vencimiento){
         $fecha_vto= Yii::$app->formatter->asDate($vencimiento->TV_FECVEN,'php:d-m-Y');
         $vencimiento_result['fecha'] = $fecha_vto;
         $vencimiento_result['saldo'] = $vencimiento->TV_SALDO;         
       }
       else{
         $fecha_vto='';
         $vencimiento_result['fecha'] = '';
         $vencimiento_result['saldo'] = '';         
       }

       

       return \yii\helpers\Json::encode($vencimiento_result);
        
    }

     /*
    Obtiene los renglones de una receta electrónica
    */
    public function actionRecetas_renglones()
    {

      $nroreceta = $_POST['nroreceta'];
      $hiscli = $_POST['hiscli'];

      $query =  Receta_electronica_renglones::find()->where(['RE_NRORECETA' => $nroreceta]);
       
        
       $renglones_receta = $query->all(); 

       $result = array();
       
       foreach($renglones_receta as $item):

           $result[] = array(
               'codmon'   => $item->RE_CODMON,
               'descripcion' => $item->rECODMON->AG_NOMBRE,

           );
        endforeach;

        return \yii\helpers\Json::encode( $result );
    }

    public function actionCantidad_acumulada()
    {
      $codmon = $_POST['codmon'];
      $hiscli = $_POST['hiscli'];

      $result = $this->cantidad_retiros_x_medicamento($hiscli,$codmon);

      return \yii\helpers\Json::encode( $result );

    }

     /*
    Obtiene los renglones de un Programa
    */
    public function actionPrograma_renglones()
    {

      $programa = $_POST['programa'];
      
      $query =  Programa_medicamentos::find()->where(['PM_CODPROG' => $programa]);
       
        
       $renglones_programa = $query->all(); 

       $result = array();
       
       foreach($renglones_programa as $item):

           $result[] = array(
               'codmon'   => $item->PM_CODMON,
               'descripcion' => $item->pMCODMON->AG_NOMBRE,
               'cantidad' => $item->PM_CANTENT,

           );
        endforeach;

        return \yii\helpers\Json::encode( $result );
    }

   
    public function actionVerificar_stock()
    {
      $codmon = $_POST['codmon'];
      $deposito = $_POST['deposito'];
      $cant_pedida = (float)$_POST['cant_pedida'];

      $vale = new Ambulatorios_ventanilla();
      $cantidad = (float)$vale->cantidad_vigente($codmon,$deposito);

       if ( $cantidad < $cant_pedida)
        $vencimiento_result['cant_a_retirar'] = $cantidad;         
       else
        $vencimiento_result['cant_a_retirar'] = $cant_pedida;         
       

       return \yii\helpers\Json::encode($vencimiento_result);

    }

    private function generarPdf($id){
      
      $model = $this->findModel($id);

      $content = $this->renderPartial('impresion', ['model' => $model]);
      
      $nombre = $id."_". date('d-m-Y')."_".date('Hi').".pdf";

      $nombre = Yii::$app->params['local_path']['path_farmacia']."/suministros_ventanilla/".$nombre;

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

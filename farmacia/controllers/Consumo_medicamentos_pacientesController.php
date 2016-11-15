<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Consumo_medicamentos_pacientes;
use farmacia\models\Consumo_medicamentos_pacientes_renglones;
use farmacia\models\Consumo_medicamentos_pacientesSearch;
use farmacia\models\ValeEnfermeriaSearch;
use farmacia\models\ValeEnfermeria;
use farmacia\models\ValeEnfermeriaRenglones;
use farmacia\models\Numero_remito;
use farmacia\models\Numero_remitoSearch;
use farmacia\models\Vencimientos;
use farmacia\models\Movimientos_diarios;
use farmacia\models\Movimientos_sala;
use farmacia\models\ArticGral;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider ;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
/**
 * Consumo_medicamentos_pacientesController implements the CRUD actions for Consumo_medicamentos_pacientes model.
 */
class Consumo_medicamentos_pacientesController extends Controller
{
    public $CodController="004";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
        {
            return [
                'access'=>[
                    'class'=>AccessControl::classname(),
                    'only'=>['index','create','view','update','delete','seleccion_servicio','iniciar_creacion'],

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
     * Lists all Consumo_medicamentos_pacientes models.
     * @return mixed
     */
    public function actionIndex($condpac)
    {
        $searchModel = new Consumo_medicamentos_pacientesSearch();
        $params = Yii::$app->request->queryParams;
        $params[$searchModel->formName()]['CM_CONDPAC'] = $condpac;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'condpac' => $condpac,
        ]);
    }

    /**
     * Displays a single Consumo_medicamentos_pacientes model.
     * @param string $CM_NROREM
     * @param integer $CM_NROVAL
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel( $id),
        ]);
    }

     public function actionSeleccion_servicio($condpac)
    {
        $searchModel = new ValeEnfermeriaSearch();
        
        $dataProvider = $searchModel->vales_servicio_listado($condpac);


        return $this->render('seleccion_servicio', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'condpac' => $condpac,
        ]);

        
    }
    //Ultima entrega de medicamento que se efectuo
    private function ultima_salida($codmon,$deposito){

      $query1 = (new \yii\db\Query())
          ->select("max(cm_fecha) as fecha")
          ->from('valefar')
          ->join('INNER JOIN', 'consmed', 'consmed.cm_nroval = valefar.va_nrovale')
          ->where(['va_codmon' => $codmon,'VA_DEPOSITO' => $deposito]);

      $query2 = (new \yii\db\Query())
          ->select("max(am_fecha) as fecha")
          ->from('ambu_ren')
          ->join('INNER JOIN', 'ambu_enc', 'ambu_enc.am_numvale = ambu_enc.am_numvale')
          ->where(['am_codmon' => $codmon,'ambu_enc.AM_DEPOSITO' => $deposito]);


      $unionQuery = (new \yii\db\Query())
          ->select("max(fecha) as ult_fecha")
          ->from(['salidas' => $query1->union($query2)]);
         
       
       return $unionQuery->one()['ult_fecha'];

    }
    private function deshacer_renglones($model)
    {

       $renglones = Consumo_medicamentos_pacientes_renglones::find()->where(['VA_NROVALE' => $model->CM_NROVAL])->all();

       
       foreach ($renglones as $key => $renglon) {


          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->VA_CODMON,
                                                      'AG_DEPOSITO' => $renglon->VA_DEPOSITO,
                                                      'AG_FRACSAL' => 'N',
                                                      ])->one();

          if ($artic_gral){

            $artic_gral->AG_STACT += $renglon->VA_CANTID;
                      
            $artic_gral->AG_ULTSAL = $this->ultima_salida($renglon->VA_CODMON,$renglon->VA_DEPOSITO);

            $artic_gral->save();

            //Incrementamos la cantidad en Vencimientos
            $vencimiento = Vencimientos::find()->where(['TV_CODART' => $renglon->VA_CODMON,
                                                        'TV_FECVEN' => $renglon->VA_FECVTO,
                                                        'TV_DEPOSITO' => $renglon->VA_DEPOSITO,
                                                        ])->one();

            if (count($vencimiento)==1){
                $vencimiento->TV_SALDO += $renglon->VA_CANTID;
                $vencimiento->save();

            }

            $movimiento_sala = Movimientos_sala::find()->where(['MO_CODMON' => $renglon->VA_CODMON,
                                                        'MO_DEPOSITO' => $renglon->VA_DEPOSITO,
                                                        'MO_TIPMOV' => "E",
                                                        'MO_FECHA' => $model->CM_FECHA,
                                                        'MO_CODSERV' => $model->CM_SERSOL,
                                                        'MO_HISCLI' => $model->CM_HISCLI,
                                                        ])->one();
                                       
         
            if ($movimiento_sala){
                $movimiento_sala->MO_CANT -= $renglon->VA_CANTID;
                if ($movimiento_sala->MO_CANT <=0){
                  $movimiento_sala->delete();
                }
                else{
                  $movimiento_sala->save();      
                }
            }

          
          }

          //Registramos el Movimiento Diario
          $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->VA_CODMON,
                                                      'MD_FECVEN' => $renglon->VA_FECVTO,
                                                      'MD_DEPOSITO' => $renglon->VA_DEPOSITO,
                                                      'MD_CODMOV' => "V",
                                                      'MD_FECHA' => $model->CM_FECHA,
                                                      ])->one();
                                     
       
          if ($movimiento){
              $movimiento->MD_CANT -= $renglon->VA_CANTID;
              if ($movimiento->MD_CANT <=0){
                $movimiento->delete();
              }
              else{
                $movimiento->save();      
              }

          }

            
           

          $renglon->delete();
          

       }

    }


    private function guardar_renglones($model)
    {

     
       $num_renglon = 1;
       foreach ($model->renglones as $key => $obj) {
          
          $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $obj['VA_CODMON'],
                                                      'AG_DEPOSITO' => $model->CM_DEPOSITO,
                                                      'AG_FRACSAL' => 'N',
                                                      ])->one();

          //Solo se actualiza stock si el medicamento No se fracciona en Sala


          if ($artic_gral){
              $artic_gral->AG_STACT -= $obj['VA_CANTID'];
              $artic_gral->AG_ULTSAL = date('Y-m-d');
              $artic_gral->save();
          }
          //Decrementamos la cantidad en Vencimientos
          $cantidad_entregada = $obj['VA_CANTID'];
          
          $cantidad_entrega_renglon = 0;
          $cantidad_pedida_renglon = 0;
          while ( $cantidad_entregada > 0) {
              $vencimiento_vigente = $this->vencimiento_vigente($obj['VA_CODMON'],$model->CM_DEPOSITO);

              if ($vencimiento_vigente){
                if ($vencimiento_vigente->TV_SALDO < $cantidad_entregada){
                  $cantidad_entregada -= $vencimiento_vigente->TV_SALDO;
                  $cantidad_renglon = $vencimiento_vigente->TV_SALDO;
                  //$cantidad_pedida_renglon = $cantidad_renglon;
                  //$cantidad_pedida -= $cantidad_pedida_renglon;
                  $vencimiento_vigente->TV_SALDO = 0;
                }else{
                  $vencimiento_vigente->TV_SALDO -= $cantidad_entregada;
                  $cantidad_renglon = $cantidad_entregada;
                  //$cantidad_pedida_renglon = $cantidad_pedida;
                  $cantidad_entregada =0;
                }
                if ($artic_gral){
                  $vencimiento_vigente->save();
                }
                //Se guarda cada renglón del Vale
                $renglon = new Consumo_medicamentos_pacientes_renglones();

                $renglon->VA_NROVALE = $model->CM_NROVAL;
                $renglon->VA_DEPOSITO = $model->CM_DEPOSITO;
                $renglon->VA_CODMON = $obj['VA_CODMON'];
                $renglon->VA_NUMRENG = $num_renglon;
                $renglon->VA_FECVTO =  $vencimiento_vigente->TV_FECVEN;
                $renglon->VA_CANTID = $cantidad_renglon;
               

                $renglon->save();
                $num_renglon++;   
                
                if ($artic_gral){
                  //Registramos el Movimiento Diario
                  $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->VA_CODMON,
                                                              'MD_FECVEN' => $renglon->VA_FECVTO,
                                                              'MD_DEPOSITO' => $renglon->VA_DEPOSITO,
                                                              'MD_CODMOV' => "V",
                                                              'MD_FECHA' => $model->CM_FECHA,
                                                              ])->one();
               
                  if ($movimiento){
                      $movimiento->MD_CANT += $renglon->VA_CANTID;
                  }
                  else{
                      $movimiento = new Movimientos_diarios();
                      $movimiento->MD_FECHA = $model->CM_FECHA;
                      $movimiento->MD_CODMOV = "V";
                      $movimiento->MD_CANT = $renglon->VA_CANTID;
                      $movimiento->MD_FECVEN =  $renglon->VA_FECVTO;
                      $movimiento->MD_CODMON = $renglon->VA_CODMON;
                      $movimiento->MD_DEPOSITO = $renglon->VA_DEPOSITO;
                  }
                  $movimiento->save();   
                }                  
              }
          }
        
          if ($artic_gral){
            //Tipo de Movimiento según condición del Paciente
            if ($model->CM_CONDPAC=='A') {
              $tipmov = 'N';
            }else{
              $tipmov = 'E';
            }
            $movimiento_sala = Movimientos_sala::find()->where(['MO_CODMON' => $obj['VA_CODMON'],
                                                      'MO_DEPOSITO' => $model->CM_DEPOSITO,
                                                      'MO_TIPMOV' => $tipmov,
                                                      'MO_FECHA' => $model->CM_FECHA,
                                                      'MO_CODSERV' => $model->CM_SERSOL,
                                                      'MO_HISCLI' => $model->CM_HISCLI,
                                                      ])->one();
            if ($movimiento_sala){
                $movimiento_sala->MO_CANT += $obj['VA_CANTID'];
            }
            else{

                $movimiento_sala = new Movimientos_sala();
                $movimiento_sala->MO_FECHA = $model->CM_FECHA;
                $movimiento_sala->MO_HORA = $model->CM_HORA;
                $movimiento_sala->MO_TIPMOV = $tipmov;
                $movimiento_sala->MO_CANT = $obj['VA_CANTID'];
                $movimiento_sala->MO_CODMON = $obj['VA_CODMON'];
                $movimiento_sala->MO_DEPOSITO = $model->CM_DEPOSITO;
                $movimiento_sala->MO_CODSERV =  $model->CM_SERSOL;
                $movimiento_sala->MO_HISCLI =  $model->CM_HISCLI;

            }
            if (!$movimiento_sala->save())
            {
              print_r($movimiento_sala->errors);die();
            }
          }
       }
    }

    /**
     * Creates a new Consumo_medicamentos_pacientes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($servicio,$condpac)
    {
        
        $model = new Numero_remito();
        $model->VR_CONDPAC = $condpac;

        if ($model->load(Yii::$app->request->post())) {
            
            //Si estoy creando un nuevo Remito guardo los datos en VALI_REM
            if ($_POST['es_remito_nuevo']=='1'){
                $nuevo_remito = $this->findRemito($model->VR_SERSOL,$model->VR_CONDPAC);
                $nuevo_remito->VR_NROREM = $model->VR_NROREM;
                $nuevo_remito->VR_SERSOL = $model->VR_SERSOL;
                $nuevo_remito->VR_CONDPAC = $model->VR_CONDPAC;
                $nuevo_remito->VR_FECDES = $model->VR_FECDES;
                $nuevo_remito->VR_FECHAS = $model->VR_FECHAS;
                $nuevo_remito->VR_HORDES = $model->VR_HORDES;
                $nuevo_remito->VR_HORHAS = $model->VR_HORHAS;

                $nuevo_remito->save();
            }

            $vale = new Consumo_medicamentos_pacientes();
            $vale->scenario = 'create';
            $vale->CM_SERSOL = $model->VR_SERSOL;
            $vale->CM_FECHA = date('Y-m-d');
            $vale->CM_HORA = date('H:i:s');
            $vale->CM_NROREM = $model->VR_NROREM;
            $vale->CM_CONDPAC = $model->VR_CONDPAC;
            
            $vale->CM_DEPOSITO = '02'; //Deposito de Internados
            
            $vale->CM_CODOPE = Yii::$app->user->identity->LE_NUMLEGA; //El usuario logueado

            $searchModel = new ValeEnfermeriaSearch();
            $params[$searchModel->formName()]['VE_SERSOL'] = $model->VR_SERSOL; 
            $params[$searchModel->formName()]['VE_PROCESADO'] = 0; 
            $params[$searchModel->formName()]['VE_CONDPAC'] = $model->VR_CONDPAC;
            
            $dataProvider = $searchModel->search($params);
            $dataProvider->setSort(false);

            return $this->render('create', [
                'model' => $vale,
                'dataProvider' => $dataProvider,
                'numero_remito' => $model,
            ]);

        } else {
            
            $searchModel = new Numero_remitoSearch();

            $model = $searchModel->numero_servicio($servicio,$condpac);    
            
            return $this->render('numero_remito', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreate_vale()
    {
        
        $model = new Consumo_medicamentos_pacientes();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {
            if ($model->CM_CONDPAC=='I'){
              $model->sala = $_POST['Consumo_medicamentos_pacientes']['sala'];
              $model->habitacion = $_POST['Consumo_medicamentos_pacientes']['habitacion'];
              $model->cama = $_POST['Consumo_medicamentos_pacientes']['cama'];
              $model->ingreso = $_POST['Consumo_medicamentos_pacientes']['ingreso'];
            }

            $model->etiquetas = $_POST['Consumo_medicamentos_pacientes']['etiquetas'];
            $model->vale_enfermeria = $_POST['Consumo_medicamentos_pacientes']['vale_enfermeria'];
            
            $valeenf = ValeEnfermeria::findOne(['VE_NUMVALE' => $model->vale_enfermeria]);
            
            $model->CM_UNIDIAG =$valeenf->VE_UDSOL;
            $model->CM_SUPERV = $valeenf->VE_SUPERV;
            $model->CM_CONDPAC = $valeenf->VE_CONDPAC;
            $model->CM_PROCESADO = 0;
                                    
            if ($model->validate() ){   
                
                 $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                    //Se guarda encabezado del Vale

                     if ($model->save()){
                        $this->guardar_renglones($model);
                      }

                      $valeenf->VE_PROCESADO = 1;
                      $valeenf->save();

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->CM_NROVAL]);
                    
                }
                catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
            else{
                 $dataProvider= new ArrayDataProvider([
                                'allModels' => [],]);

                 $numero_remito = $this->findRemito($model->CM_SERSOL,$model->CM_CONDPAC);

                return $this->render('create', [
                 'model' => $model,
                 'dataProvider' => $dataProvider,
                 'numero_remito' => $numero_remito,
                ]);

            }
            
        } 
    }
    /**
     * Updates an existing Consumo_medicamentos_pacientes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $CM_NROREM
     * @param integer $CM_NROVAL
     * @return mixed
     */
    public function actionUpdate( $id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        
        if ($model->load(Yii::$app->request->post()))  {
               
               if ($model->validate() ){            
          
                  $connection = \Yii::$app->db;
                  $transaction = $connection->beginTransaction();

                  try {
                      //Se guarda encabezado Vale Farmacia
                      if ($model->save()){
                        $this->deshacer_renglones($model);
                        $this->guardar_renglones($model);
                      }
                      $transaction->commit();
                     return $this->redirect(['view', 'id' => $model->CM_NROVAL]);
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

              $renglones = Consumo_medicamentos_pacientes_renglones::find()->where(['VA_NROVALE' => $model->CM_NROVAL])->all();

              $renglones = $this->agrupar_medicamentos($renglones);
              
              foreach ($renglones as $key => $renglon) {
                $renglones[$key]->descripcion = $renglon->monodroga->AG_NOMBRE;
                $renglon->VA_FECVTO = Yii::$app->formatter->asDate($renglon->VA_FECVTO,'php:d-m-Y');
              }

              $model->renglones = $renglones;

              
              return $this->render('update', [
                  'model' => $model,
                   
              ]);
          }

    }

    /**
     * Deletes an existing Consumo_medicamentos_pacientes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $CM_NROREM
     * @param integer $CM_NROVAL
     * @return mixed
     */
    public function actionDelete($id)
    {
        
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        try {
          $model = $this->findModel($id);
          $condpac = $model->CM_CONDPAC;
          $this->deshacer_renglones($model);

          $model->delete();
          
          $transaction->commit();
          
          return $this->redirect(['index','condpac'=>$condpac]);

        }
        catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Finds the Consumo_medicamentos_pacientes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $CM_NROREM
     * @param integer $CM_NROVAL
     * @return Consumo_medicamentos_pacientes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $CM_NROVAL)
    {
        if (($model = Consumo_medicamentos_pacientes::findOne(['CM_NROVAL' => $CM_NROVAL])) !== null) {
            $searchModel = new Consumo_medicamentos_pacientes_renglones();
            $model->renglones =  $searchModel->get_renglones($CM_NROVAL);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findRemito($servicio,$condpac)
    {
        if (($model = Numero_remito::findOne(['VR_SERSOL' => $servicio,'VR_CONDPAC' => $condpac])) !== null) {
            return $model;
        } else {
            return new Numero_remito();
        }
    }

    public function actionVale_enfermeria_renglones()
    {

      $nrovale = $_POST['valeenf'];
           

      $query =  ValeEnfermeriaRenglones::find()->where(['VE_NUMVALE' => $nrovale]);
             
       $renglones_vale_enfermeria = $query->all(); 

       $result = array();
       
       foreach($renglones_vale_enfermeria as $item):

           $result[] = array(
               'codmon'   => $item->VE_CODMON,
               'descripcion' => $item->monodroga->AG_NOMBRE,
               'cantidad'   => $item->VE_CANTID,

           );
        endforeach;

        $datos_vale['renglones']=$result;

        $searchModel = new ValeEnfermeriaSearch();

        $etiquetas = $searchModel->etiquetas($nrovale);

        // $etiqueta = str_replace("../..", "", '../../data/etiquetas/979_PROBLEMAS-CARDIACOS.png');
        // $etiqueta1 = str_replace("../..", "", '../../data/etiquetas/245_TRASTORNOS-DEL-SUEÑO.png');

        $datos_vale['etiquetas']=$etiquetas;

        return \yii\helpers\Json::encode( $datos_vale );
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

    private function agrupar_medicamentos($renglones){
      
      $codigos_medicamentos=[];
      $codmon = '';
      $cant_renglones = count($renglones);
      $nuevos_renglones = [];

      $sum_cant_ent = 0;
      $sum_cant_ped = 0;

      for ($i=0; $i < $cant_renglones; $i++) { 
           
        $renglon = $renglones[$i];



        if ($renglon->VA_CODMON!=$codmon){
          
          if ($codmon != ''){

            $renglon_nuevo->VA_CANTID = number_format($sum_cant_ent, 2, '.', '');
            $nuevos_renglones[] = $renglon_nuevo;

            $renglon_nuevo = $renglon;
            $codmon = $renglon_nuevo->VA_CODMON;
            $sum_cant_ent = $renglon->VA_CANTID;
            
            
          }else{
            $renglon_nuevo = $renglon;
            $codmon = $renglon_nuevo->VA_CODMON;
            $sum_cant_ent += $renglon->VA_CANTID;
            
          }
          if (($i+1)==$cant_renglones){
              $nuevos_renglones[] = $renglon_nuevo;              
            }
        }else{
          
           $sum_cant_ent += $renglon->VA_CANTID;
           

           if (($i+1)==$cant_renglones){
              $renglon_nuevo->VA_CANTID = number_format($sum_cant_ent, 2, '.', '');
              $nuevos_renglones[] = $renglon_nuevo;            
           }
          
        }

      }

      return $nuevos_renglones;
    }

     public function actionImprimir($id) {

      header('Content-Type: application/pdf');
    
      $model = $this->findModel($id);

      $content = $this->renderPartial('impresion_vale', ['model' => $model]);
  
      $pdf = new Pdf([
          'mode' => Pdf::MODE_UTF8,
          'format' => Pdf::FORMAT_A4, 
          'orientation' => Pdf::ORIENT_PORTRAIT, 
          'destination' => Pdf::DEST_BROWSER, 
          'content' => $content,
          'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
          'cssInline' => '.kv-heading-1{font-size:18px}', 
           'options' => ['title' => 'Vale Farmacia'],
      ]);
     
       return $pdf->render(); 
    }

    public function actionImprimir_remito($id,$condpac) {

      header('Content-Type: application/pdf');
      
      if (($model = Consumo_medicamentos_pacientes::findOne(['CM_NROREM' => $id,'CM_CONDPAC'=>$condpac])) !== null) {
            $model->renglones =  $model->get_renglones_remito($id,$condpac);
      }   

      $content = $this->renderPartial('impresion_remito', ['model' => $model]);
 
      $pdf = new Pdf([
          'mode' => Pdf::MODE_UTF8,
          'format' => Pdf::FORMAT_A4, 
          'orientation' => Pdf::ORIENT_PORTRAIT, 
          'destination' => Pdf::DEST_BROWSER, 
          'content' => $content,
          'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
          'cssInline' => '.kv-heading-1{font-size:18px}', 
          'options' => ['title' => 'Remito Pacientes Farmacia'],
      ]);
       return $pdf->render(); 
    }

     public function actionProcesar($id,$condpac) {

      $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        try {
          $vales = Consumo_medicamentos_pacientes::find()->where(['CM_NROREM' => $id,'CM_CONDPAC'=>$condpac])->all();

          foreach ($vales as $key => $vale) {
            $vale->renglones = "renglones";
            $vale->CM_PROCESADO = 1;
            if (!$vale->save())
              $error = $vale->errors;
            else
              $error = "success";

            $this->generarPdf($vale->CM_NROVAL);
          }
          

          $transaction->commit();
          
          return \yii\helpers\Json::encode( $error );

        }
        catch (\Exception $e) {
            $transaction->rollBack();
            return \yii\helpers\Json::encode( $e );
        }

    }

    private function generarPdf($id){
      
      header('Content-Type: application/pdf');
    
      $model = $this->findModel($id);

      $content = $this->renderPartial('impresion_vale', ['model' => $model]);
      
      $nombre = $id."_". date('d-m-Y')."_".date('Hi').".pdf";

      $nombre = Yii::$app->params['local_path']['path_farmacia']."/suministros_sala_paciente/".$nombre;

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
          'options' => ['title' => 'Vale Farmacia'],
      ]);
      
      $pdf->render();

    }
}

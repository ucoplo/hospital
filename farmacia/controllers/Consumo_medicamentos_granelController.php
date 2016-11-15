<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Consumo_medicamentos_granel;
use farmacia\models\Consumo_medicamentos_granelSearch;
use farmacia\models\ValeGranelSearch;
use farmacia\models\ValeGranel;
use farmacia\models\Consumo_medicamentos_granel_renglones;
use farmacia\models\Vencimientos;
use farmacia\models\ArticGral;
use farmacia\models\Movimientos_diarios;
use farmacia\models\Movimientos_sala;
use farmacia\models\Movimientos_quirofano;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;


/**
 * Consumo_medicamentos_granelController implements the CRUD actions for Consumo_medicamentos_granel model.
 */
class Consumo_medicamentos_granelController extends Controller
{
   public $CodController="005";
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
     * Lists all Consumo_medicamentos_granel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Consumo_medicamentos_granelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     public function actionSeleccion_servicio()
    {
        $searchModel = new ValeGranelSearch();
        
        $dataProvider = $searchModel->vales_servicio_listado();


        return $this->render('seleccion_servicio', [
            'dataProvider' => $dataProvider,
        ]);

        
    }

    /**
     * Displays a single Consumo_medicamentos_granel model.
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
         $model = new Consumo_medicamentos_granel();

         $vale_granel = ValeGranel::findOne($vale);

         $model->CM_SERSOL = $vale_granel->VM_SERSOL;
         $model->CM_DEPOSITO = $vale_granel->VM_DEPOSITO;
         $model->CM_FECHA = date('Y-m-d');
         $model->CM_HORA = date('H:i:s');
         $model->CM_CODOPE = Yii::$app->user->identity->LE_NUMLEGA; //El usuario logueado
         $model->CM_ENFERM = $vale_granel->VM_SUPERV;

         $model->vale_granel = $vale;

         $renglones = [];

          foreach ($vale_granel->renglones as $key => $renglon) {
                $nuevo_renglon = new Consumo_medicamentos_granel_renglones();           
                $nuevo_renglon->PF_CODMON = $renglon->VM_CODMON;
                $nuevo_renglon->PF_CANTID = $renglon->VM_CANTID;
                $nuevo_renglon->descripcion = $renglon->monodroga->AG_NOMBRE;
                $fecha = $this->vencimiento_vigente($nuevo_renglon->PF_CODMON,'02');
                if ($fecha)
                    $nuevo_renglon->PF_FECVTO = Yii::$app->formatter->asDate($fecha->TV_FECVEN,'php:d-m-Y');
                
                $renglones[] = $nuevo_renglon;
              }

        $model->renglones = $renglones;

         return $this->render('create', [
                'model' => $model,
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
       $renglones = Consumo_medicamentos_granel_renglones::find()->where(['PF_NROREM' => $model->CM_NROREM])->all();
              
       foreach ($renglones as $key => $renglon) {


        $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $renglon->PF_CODMON,
                                                      'AG_DEPOSITO' => $renglon->PF_DEPOSITO,
                                                      
                                                      ])->one();

        if ($artic_gral){

            $artic_gral->AG_STACT += $renglon->PF_CANTID;
                      
            $artic_gral->AG_ULTSAL = $this->ultima_salida($renglon->PF_CODMON,$renglon->PF_DEPOSITO);

            $artic_gral->save();
        }   
            //Incrementamos la cantidad en Vencimientos
            $vencimiento = Vencimientos::find()->where(['TV_CODART' => $renglon->PF_CODMON,
                                                        'TV_FECVEN' => $renglon->PF_FECVTO,
                                                        'TV_DEPOSITO' => $renglon->PF_DEPOSITO,
                                                        ])->one();

        if (count($vencimiento)==1){
            $vencimiento->TV_SALDO += $renglon->PF_CANTID;
            $vencimiento->save();

        }

        //Registramos el Movimiento Diario
        $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->PF_CODMON,
                                                      'MD_FECVEN' => $renglon->PF_FECVTO,
                                                      'MD_DEPOSITO' => $renglon->PF_DEPOSITO,
                                                      'MD_CODMOV' => "V",
                                                      'MD_FECHA' => $model->CM_FECHA,
                                                      ])->one();
                                     
          if ($movimiento){
              $movimiento->MD_CANT -= $renglon->PF_CANTID;
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
          
        $artic_gral = ArticGral::find()->where(['AG_CODIGO' => $obj['PF_CODMON'],
                                                'AG_DEPOSITO' => $model->CM_DEPOSITO,
                                               ])->one();

        //Solo se actualiza stock si el medicamento No se fracciona en Sala
        if ($artic_gral){
            $artic_gral->AG_STACT -= $obj['PF_CANTID'];
            $artic_gral->AG_ULTSAL = date('Y-m-d');
            $artic_gral->save();
        
          //Decrementamos la cantidad en Vencimientos
          $cantidad_entregada = $obj['PF_CANTID'];
          
          while ( $cantidad_entregada > 0) {
              $vencimiento_vigente = $this->vencimiento_vigente($obj['PF_CODMON'],$model->CM_DEPOSITO);

              if ($vencimiento_vigente){
                if ($vencimiento_vigente->TV_SALDO < $cantidad_entregada){
                  $cantidad_entregada -= $vencimiento_vigente->TV_SALDO;
                  $cantidad_renglon = $vencimiento_vigente->TV_SALDO;
                  $vencimiento_vigente->TV_SALDO = 0;
                }else{
                  $vencimiento_vigente->TV_SALDO -= $cantidad_entregada;
                  $cantidad_renglon = $cantidad_entregada;
                  $cantidad_entregada =0;
                }
                $vencimiento_vigente->save();
                //Se guarda cada renglón del Vale
                $renglon = new Consumo_medicamentos_granel_renglones();

                $renglon->PF_NROREM = $model->CM_NROREM;
                $renglon->PF_DEPOSITO = $model->CM_DEPOSITO;
                $renglon->PF_CODMON = $obj['PF_CODMON'];
                
                $renglon->PF_FECVTO =  $vencimiento_vigente->TV_FECVEN;
                $renglon->PF_CANTID = $cantidad_renglon;
               

                $renglon->save();

                //Registramos el Movimiento Diario
                $movimiento = Movimientos_diarios::find()->where(['MD_CODMON' => $renglon->PF_CODMON,
                                                            'MD_FECVEN' => $renglon->PF_FECVTO,
                                                            'MD_DEPOSITO' => $renglon->PF_DEPOSITO,
                                                            'MD_CODMOV' => "V",
                                                            'MD_FECHA' => $model->CM_FECHA,
                                                            ])->one();
                                           
             
                if ($movimiento){
                    $movimiento->MD_CANT += $renglon->PF_CANTID;
                }
                else{

                    $movimiento = new Movimientos_diarios();
                    $movimiento->MD_FECHA = $model->CM_FECHA;
                    $movimiento->MD_CODMOV = "V";
                    $movimiento->MD_CANT = $renglon->PF_CANTID;
                    $movimiento->MD_FECVEN =  $renglon->PF_FECVTO;
                    $movimiento->MD_CODMON = $renglon->PF_CODMON;
                    $movimiento->MD_DEPOSITO = $renglon->PF_DEPOSITO;

                }

                $movimiento->save();

                $num_renglon++;   
                                      
              }
          }
          
          $servicios_quirofano = Yii::$app->params['servicios_quirofano'];

          //Si se fracciona en Quirofana la cantidad se multiplica por las unidades del envase
          if ($artic_gral->AG_FRACCQ=='S'){
            $cantidad_entrada = (isset($artic_gral->AG_UNIENV)) ? $obj['PF_CANTID']*$artic_gral->AG_UNIENV : $obj['PF_CANTID'];
          }else{
            $cantidad_entrada = $obj['PF_CANTID'];
          }
         
          if (in_array($model->CM_SERSOL,$servicios_quirofano )){

            $movimiento_quirofano = Movimientos_quirofano::find()->where(['MO_CODART' => $obj['PF_CODMON'],
                                                        'MO_DEPOSITO' =>$model->CM_DEPOSITO,
                                                        'MO_TIPMOV' => "F",
                                                        'MO_FECHA' => $model->CM_FECHA,
                                                        'MO_IDFOJA' => null,
                                                        'MO_SECTOR' => $model->CM_SERSOL
                                                        ])->one();
                                       
         
            if ($movimiento_quirofano){
                $movimiento_quirofano->MO_CANTIDA += $cantidad_entrada;
            }
            else{

                $movimiento_quirofano = new Movimientos_quirofano();
                $movimiento_quirofano->MO_FECHA = $model->CM_FECHA;
                $movimiento_quirofano->MO_HORA = $model->CM_HORA;
                $movimiento_quirofano->MO_SECTOR = $model->CM_SERSOL;
                $movimiento_quirofano->MO_TIPMOV = "F";
                $movimiento_quirofano->MO_CANTIDA = $cantidad_entrada;
                $movimiento_quirofano->MO_CODART = $obj['PF_CODMON'];
                $movimiento_quirofano->MO_DEPOSITO = $model->CM_DEPOSITO;
               
            }
            
            if (!$movimiento_quirofano->save())
            {
              print_r($movimiento_quirofano->errors);
            }

          }
          else{
            $movimiento_sala = Movimientos_sala::find()->where(['MO_CODMON' => $obj['PF_CODMON'],
                                                        'MO_DEPOSITO' =>$model->CM_DEPOSITO,
                                                        'MO_TIPMOV' => "F",
                                                        'MO_FECHA' => $model->CM_FECHA,
                                                        'MO_CODSERV' => $model->CM_SERSOL,
                                                        'MO_HISCLI' => null,
                                                        ])->one();
                                       
         
            if ($movimiento_sala){
                $movimiento_sala->MO_CANT += $obj['PF_CANTID'];
            }
            else{

                $movimiento_sala = new Movimientos_sala();
                $movimiento_sala->MO_FECHA = $model->CM_FECHA;
                $movimiento_sala->MO_HORA = $model->CM_HORA;
                $movimiento_sala->MO_TIPMOV = "F";
                $movimiento_sala->MO_CANT = $obj['PF_CANTID'];
                $movimiento_sala->MO_CODMON = $obj['PF_CODMON'];
                $movimiento_sala->MO_DEPOSITO = $model->CM_DEPOSITO;
                $movimiento_sala->MO_CODSERV =  $model->CM_SERSOL;
                

            }
            
            if (!$movimiento_sala->save())
            {
              print_r($movimiento_sala->errors);
            }
          }
        }
      }
    }
    /**
     * Creates a new Consumo_medicamentos_granel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Consumo_medicamentos_granel();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // $model->vale_granel = $_POST['Consumo_medicamentos_granel']['vale_granel'];
            
            if ($model->validate() ){   
                
                $valegranel = ValeGranel::findOne($model->vale_granel);
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                    //Se guarda encabezado del Vale
                     $model->CM_PROCESADO = 0;
                     if ($model->save()){
                        $this->guardar_renglones($model);
                      }

                      $valegranel->VM_PROCESADO = 1;
                      $valegranel->save();

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->CM_NROREM]);
                    
                }
                catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            } else {
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
     * Updates an existing Consumo_medicamentos_granel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post())) {
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
                     return $this->redirect(['view', 'id' => $model->CM_NROREM]);
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
            

              $renglones = $this->agrupar_medicamentos($model->renglones);
              
              foreach ($renglones as $key => $renglon) {
                $renglones[$key]->descripcion = $renglon->monodroga->AG_NOMBRE;
                $renglon->PF_FECVTO = Yii::$app->formatter->asDate($renglon->PF_FECVTO,'php:d-m-Y');
              }

              $model->renglones = $renglones;

              
              return $this->render('update', [
                  'model' => $model,
                   
              ]);
           
        }
    }

    /**
     * Deletes an existing Consumo_medicamentos_granel model.
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
     * Finds the Consumo_medicamentos_granel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Consumo_medicamentos_granel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Consumo_medicamentos_granel::findOne($id)) !== null) {

            $searchModel = new Consumo_medicamentos_granel_renglones();
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

    private function agrupar_medicamentos($renglones){
      
      $codigos_medicamentos=[];
      $codmon = '';
      $cant_renglones = count($renglones);
      $nuevos_renglones = [];

      $sum_cant_ent = 0;
      $sum_cant_ped = 0;
      $renglones = $renglones->getModels();
      for ($i=0; $i < $cant_renglones; $i++) { 
      
        $renglon = $renglones[$i];



        if ($renglon->PF_CODMON!=$codmon){
          
          if ($codmon != ''){

            $renglon_nuevo->PF_CANTID = number_format($sum_cant_ent, 2, '.', '');
            $nuevos_renglones[] = $renglon_nuevo;

            $renglon_nuevo = $renglon;
            $codmon = $renglon_nuevo->PF_CODMON;
            $sum_cant_ent = $renglon->PF_CANTID;
            
            
          }else{
            $renglon_nuevo = $renglon;
            $codmon = $renglon_nuevo->PF_CODMON;
            $sum_cant_ent += $renglon->PF_CANTID;
            
          }
          if (($i+1)==$cant_renglones){
              $nuevos_renglones[] = $renglon_nuevo;              
            }
        }else{
          
           $sum_cant_ent += $renglon->PF_CANTID;
           

           if (($i+1)==$cant_renglones){
              $renglon_nuevo->PF_CANTID = number_format($sum_cant_ent, 2, '.', '');
              $nuevos_renglones[] = $renglon_nuevo;            
           }
          
        }

      }

      return $nuevos_renglones;
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
          'options' => ['title' => 'Planilla Granel'],
      ]);

       return $pdf->render(); 
      
       // $pdf = new Pdf();
       //  $mpdf = $pdf->api; // fetches mpdf api
       //  $mpdf->SetHeader('Kartik Header'); // call methods or set any properties
       //  $mpdf->WriteHtml($content); // call mpdf write html
       //  $mpdf->SetJS('console.log("aaaa");');
        
       //  echo $mpdf->Output('filename', Pdf::DEST_BROWSER); // call the mpdf api output as needed

    }

    public function actionProcesar($id) {

      $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        try {
              $model = $this->findModel($id);
              $model->CM_PROCESADO = 1;
              $model->save(); 

              $this->generarPdf($id);  
              $transaction->commit();
          
          return \yii\helpers\Json::encode( "success" );

        }
        catch (\Exception $e) {
            $transaction->rollBack();
            return \yii\helpers\Json::encode( $e );
        }
      
    }

     private function generarPdf($id){
      header('Content-Type: application/pdf');

      $model = $this->findModel($id);
  
      $content = $this->renderPartial('impresion', ['model' => $model]);
      
      $nombre = $id."_". date('d-m-Y')."_".date('Hi').".pdf";

      $nombre = Yii::$app->params['local_path']['path_farmacia']."/suministros_sala_granel/".$nombre;

      $pdf = new Pdf([
          'mode' => Pdf::MODE_UTF8,
          'format' => Pdf::FORMAT_A4, 
          'orientation' => Pdf::ORIENT_PORTRAIT, 
          'filename' => $nombre,
          'destination' => Pdf::DEST_FILE, 
          'content' => $content,
          'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
          'cssInline' => '.kv-heading-1{font-size:18px}', 
          'options' => ['title' => 'Planilla Granel'],
      ]);
      
      $pdf->render();

    }
}

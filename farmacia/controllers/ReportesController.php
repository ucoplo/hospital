<?php

namespace farmacia\controllers;

use Yii;

use farmacia\models\Movimientos_diarios;
use farmacia\models\AbcFiltro;
use farmacia\models\MedicamentosFiltro;
use farmacia\models\MonodrogasFiltro;
use farmacia\models\Ingresos_monodrogaFiltro;
use farmacia\models\Cardex_articulosFiltro;
use farmacia\models\PerdidasFiltro;
use farmacia\models\DevolucionesFiltro;
use farmacia\models\VencimientosFiltro;
use farmacia\models\UltimaSalidaFiltro;
use farmacia\models\ConsumosFiltro;
use farmacia\models\ConsumosPacienteFiltro;
use farmacia\models\ConsumosVentanillaFiltro;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider ;
use kartik\grid\GridView;
use yii\filters\AccessControl;

use yii\db\Query;
use yii\helpers\Json;
/**
 * TechoController implements the CRUD actions for Techo model.
 */
class ReportesController extends Controller
{
     public $CodController="012";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::classname(),
                'only'=>['monodrogas'],
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

    public function actionPacientelist($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('pa_hiscli as id, pa_apenom as text')
                ->from('paciente')
                ->where(['like', 'pa_apenom', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Paciente::findOne($id)->pa_apenom];
        }
        return $out;
    }

    public function actionMonodrogas()
    {
        $searchModel = new MonodrogasFiltro();
       
        $params = Yii::$app->request->queryParams;
        $params[$searchModel->formName()]['activo'] = 'T';
        $params[$searchModel->formName()]['vademecum'] = 'S';
        $dataProvider = $searchModel->buscar($params);
        
         if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
        return $this->render('monodrogas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
       
    }

    public function actionMedicamentos()
    {
        $searchModel = new MedicamentosFiltro();
        $dataProvider = $searchModel->buscar(Yii::$app->request->queryParams);
      
        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
        return $this->render('medicamentos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionIngreso_monodroga()
    {
        $searchModel = new Ingresos_monodrogaFiltro();
        $dataProvider = $searchModel->buscar(Yii::$app->request->queryParams);
        $dataProvider->sort = false;
        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }

        return $this->render('ingresos_monodroga', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionCardex_articulos()
    {
        $searchModel = new Cardex_articulosFiltro();
        $dataProvider = $searchModel->buscar(Yii::$app->request->queryParams);
        $dataProvider->sort = false;
        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
       
        $movs = $dataProvider->query->all();
     
        $fila_existencia = new Movimientos_diarios();
        $fila_existencia->load(Yii::$app->request->queryParams);
        
        if (isset($searchModel->periodo_inicio) && $searchModel->periodo_inicio!='') {
            $nuevafecha = strtotime ( '-1 day' , strtotime ( $searchModel->periodo_inicio ) ) ;
            $fecha_inicio_format = date ( 'd-m-Y' , $nuevafecha );
            $fila_existencia->concepto = 'EXISTENCIA AL '.$fecha_inicio_format;
        }else{
             $fila_existencia->concepto = 'EXISTENCIA INICIAL';
        }

        $existencia = $searchModel->existencia();
        $fila_existencia->MD_FECHA = '';
        $fila_existencia->MD_CODMON = $searchModel->monodroga;
        $fila_existencia->entrada = '';
        $fila_existencia->salida = '';
        $fila_existencia->existencia = $existencia;

        $renglon_existencia[] =$fila_existencia;

        foreach ($movs as $key => $value) {
            $value->concepto = $value->MD_CODMOV.' - '.$value->codigo->MS_NOM;
            if ($value->codigo->MS_SIGNO<0){
                $value->salida = $value->MD_CANT;
                $value->entrada = ''; 
                $existencia -= $value->salida;
                $value->existencia = $existencia;
            }
            else{
                $value->entrada = $value->MD_CANT;
                 $value->salida = '';
                 $existencia += $value->entrada;
                $value->existencia = $existencia;
            }
        }

        $movs = array_merge($renglon_existencia,$movs);
        $dataProvider =  new ArrayDataProvider([
            'allModels' => $movs,
           
        ]);

        return $this->render('cardex_articulos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }
     
     public function actionCardex_lotes()
    {
        $searchModel = new Cardex_articulosFiltro();
          
        $dataProvider = $searchModel->buscar_lotes(Yii::$app->request->queryParams);
      
        $movs = $dataProvider->query->all();
        
        $lote = '';
        $movimientos_mostrar = [];
        $existencia = 0;
        foreach ($movs as $key => $value) {
            $value->concepto = $value->MD_CODMOV.' - '.$value->codigo->MS_NOM;

            if ($lote!=$value->MD_FECVEN){
                $lote = $value->MD_FECVEN;

                $fila_existencia = new Movimientos_diarios();
                $fila_existencia->load(Yii::$app->request->queryParams);
                
                if (isset($searchModel->periodo_inicio) && $searchModel->periodo_inicio!='') {
                    $nuevafecha = strtotime ( '-1 day' , strtotime ( $searchModel->periodo_inicio ) ) ;
                    $fecha_inicio_format = date ( 'd-m-Y' , $nuevafecha );
                    $fila_existencia->concepto = 'EXISTENCIA AL '.$fecha_inicio_format;
                }else{
                     $fila_existencia->concepto = 'EXISTENCIA INICIAL';
                }

                $existencia = $searchModel->existencia($value->MD_FECVEN);
                $fila_existencia->MD_FECHA = '';
                
                $fila_existencia->entrada = '';
                $fila_existencia->salida = '';
                $fila_existencia->existencia = $existencia;
                $fila_existencia->MD_CODMON = $searchModel->monodroga;
                $fila_existencia->MD_FECVEN = $lote;

                $movimientos_mostrar[] =$fila_existencia;
            }

            if ($value->codigo->MS_SIGNO<0){
                $value->salida = $value->MD_CANT;
                $value->entrada = ''; 
                $existencia -= $value->salida;
                $value->existencia = $existencia;
            }
            else{
                $value->entrada = $value->MD_CANT;
                 $value->salida = '';
                 $existencia += $value->entrada;
                $value->existencia = $existencia;
            }

            

            $movimientos_mostrar[] = $value;
        }

        
        $dataProvider =  new ArrayDataProvider([
            'allModels' => $movimientos_mostrar,
           
        ]);


         if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }

        return $this->render('cardex_lotes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }
    
     public function actionStock()
    {
        $searchModel = new MonodrogasFiltro();
        $searchModel->activo = '';
        $searchModel->vademecum = '';  
        $dataProvider = $searchModel->buscar(Yii::$app->request->queryParams);
      

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('stock', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

     public function actionAbc()
    {
        $searchModel = new AbcFiltro();
        $searchModel->activos = '';
        
        $dataProvider = $searchModel->abc(Yii::$app->request->queryParams);
        // echo "<pre>";print_r($dataProvider->models);echo "</pre>";
        
        $movs = $dataProvider->query->all();

        $total_consumo = 0;
        foreach ($movs as $key => $mov) {
            $total_consumo += $mov->consumo_valor;
        }
        $porc_acumulado = 0;
        foreach ($movs as $key => $mov) {
            $mov->porc_abc = ($mov->consumo_valor*100)/$total_consumo;

            $porc_acumulado += $mov->porc_abc;
            $mov->porc_abc = $porc_acumulado;

            if ($porc_acumulado>=95){
                $mov->clasifica_abc = 'C';
            }elseif ($porc_acumulado>=80) {
                 $mov->clasifica_abc = 'B';
            }else{
                $mov->clasifica_abc = 'A';
            }
        }

        $dataProvider =  new ArrayDataProvider([
            'allModels' => $movs,
        ]);


       if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
       
        return $this->render('abc', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    private function consumo_servicio($consumos,$servicio){

        $total_consumo = 0;
        foreach ($consumos as $key => $mov) {
            if ($mov['servicio']==$servicio) {
                 $total_consumo += $mov['consumo_valor'];
            }
           
        }
      
        return $total_consumo; 
    }

    public function actionAbc_servicio()
    {
        $searchModel = new AbcFiltro();
        $searchModel->activos = '';
        
        $dataProvider = $searchModel->abc_servicio(Yii::$app->request->queryParams);
       
        
        $movs = $dataProvider->query->all();

       
        $porc_acumulado = 0;
        $servicio = null;
        $total_consumo = 0;
        for ($key=0; $key < count($movs); $key++) { 
          
            if (!isset($servicio) || $movs[$key]['servicio']!=$servicio){
                 $servicio = $movs[$key]['servicio'];
                 $total_consumo = $this->consumo_servicio($movs,$servicio);
                
                 $porc_acumulado = 0;
            }
               
            $movs[$key]['porc_abc'] = ( $movs[$key]['consumo_valor']*100)/$total_consumo;

            $porc_acumulado +=  $movs[$key]['porc_abc'];
            $movs[$key]['porc_abc'] = $porc_acumulado;

            if ($porc_acumulado>=95){
                $movs[$key]['clasifica_abc'] = 'C';
            }elseif ($porc_acumulado>=80) {
                 $movs[$key]['clasifica_abc'] = 'B';
            }else{
                $movs[$key]['clasifica_abc'] = 'A';
            }
        }

        $dataProvider =  new ArrayDataProvider([
            'allModels' => $movs,
        ]);


       if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
       
        return $this->render('abc_servicio', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

      public function actionIndices()
    {
        $searchModel = new ConsumosFiltro();
        $searchModel->scenario = 'indices';
        $dataProvider = $searchModel->buscar_indices(Yii::$app->request->queryParams);

        

        if  ($searchModel->load(Yii::$app->request->get())){

            $drogas = $dataProvider->query->all();

           
            for ($key=0; $key < count($drogas); $key++) { 
               $existencia_promedio = $searchModel->existencia_promedio($drogas[$key]['codmon']);
               if ($existencia_promedio>0)
                    $drogas[$key]['indice'] = $drogas[$key]['total_consumo'] / $existencia_promedio;
                else
                    $drogas[$key]['indice'] = 0;
            }

            //Ordeno resultado por indice de Rotacion
            $sortArray = array(); 

            foreach($drogas as $droga){ 
                foreach($droga as $key=>$value){ 
                    if(!isset($sortArray[$key])){ 
                        $sortArray[$key] = array(); 
                    } 
                    $sortArray[$key][] = $value; 
                } 
            } 

            $orderby = "indice"; //change this to whatever key you want from the array 

            array_multisort($sortArray[$orderby],SORT_DESC,$drogas); 
            //Fin ordenamiento
            
            $dataProvider =  new ArrayDataProvider([
                'allModels' => $drogas,
            ]);


            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('indices', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionSalida_media()
    {
        $searchModel = new ConsumosFiltro();
        $searchModel->scenario = 'salida_media';
        $salida_media = $searchModel->buscar_salida_media(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('salida_media', [
            'searchModel' => $searchModel,
            'salida_media' => $salida_media,
            'filtro' => $filtro,
        ]);
    }

    public function actionPerdidas()
    {
        $searchModel = new PerdidasFiltro();
        
        $dataProvider = $searchModel->buscar(Yii::$app->request->queryParams);
      

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('perdidas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionDevoluciones()
    {
        $searchModel = new DevolucionesFiltro();
          $searchModel->vales = 1;
        $searchModel->planillas = 1;
        $searchModel->sobrante = 1;
        
        $dataProvider = $searchModel->buscar(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('devoluciones', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionMonodrogas_vencimientos()
    {
        $searchModel = new VencimientosFiltro();
               
        $dataProvider = $searchModel->buscar(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('vencimientos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionMonodrogas_vencidas()
    {
        $searchModel = new VencimientosFiltro();
               
        $dataProvider = $searchModel->buscarVencidas(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('monodrogas_vencidas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

     public function actionMonodrogas_por_vencer()
    {
        $searchModel = new VencimientosFiltro();
               
        $dataProvider = $searchModel->buscarPorVencer(Yii::$app->request->queryParams);
        $vencimientos = $dataProvider->query->all();

        $lotes_por_vencer = [];
        foreach ($vencimientos as $key => $venc) {
            $consumo_diario = $searchModel->consumo_medio_diario($venc->TV_CODART);

            $venc->consumo_medio = $consumo_diario;
            
            $segundos=strtotime($venc->TV_FECVEN) - strtotime('now');
            $dias=intval($segundos/60/60/24);
            
           
            $saldo_futuro = $venc->TV_SALDO - ($consumo_diario*$dias);
            
            //Si no se consumira el lote y el consumo medio es mayor o igual a  cero se vencerá
            if (($saldo_futuro>0) && ($consumo_diario>=0)){
                $lotes_por_vencer[] = $venc;
            }

        }
        $dataProvider =  new ArrayDataProvider([
            'allModels' => $lotes_por_vencer,
        ]);


        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('monodrogas_por_vencer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionUltima_salida()
    {
        $searchModel = new UltimaSalidaFiltro();
        
        $searchModel->nombre1 = "Activo";
        $searchModel->limite1 = "60";
        $searchModel->nombre2 = "Dormido";
        $searchModel->limite2 = "120";
        $searchModel->nombre3 = "Obsoleto";
        $searchModel->limite3 = "180";
        $searchModel->nombre4 = "Muerto";
        $searchModel->limite4 = "365";

        $dataProvider = $searchModel->buscar(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('ultima_salida', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionConsumo_por_monodroga()
    {
        $searchModel = new ConsumosFiltro();
              
        $dataProvider = $searchModel->buscar_por_monodroga(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_por_monodroga', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionDetalleMonodroga() {
        if (isset($_POST['expandRowKey'])) {
            $searchModel = new ConsumosFiltro();
            $searchModel->load(Yii::$app->request->queryParams);
            $dataProvider = $searchModel->buscar_por_monodroga_droga($_POST['expandRowKey']);
            
            return $this->renderAjax('consumos_por_monodroga_droga', [
                'dataProvider' => $dataProvider,
                'id'=>$_POST['expandRowKey'],
            ]);
        } else {
            return '<div class="alert alert-danger">No existe información!</div>';
        }
    }
      public function actionConsumo_por_servicio()
    {
        $searchModel = new ConsumosFiltro();
              
        $dataProvider = $searchModel->buscar_por_servicio(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_por_servicio', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionDetalleMonodrogaServicio() {
        if (isset($_POST['expandRowKey'])) {
            $searchModel = new ConsumosFiltro();
            $searchModel->load(Yii::$app->request->queryParams);
            $codmon=substr($_POST['expandRowKey'], -4);
            $servicio=substr($_POST['expandRowKey'], 0, 3);
            $dataProvider = $searchModel->buscar_por_servicio_droga($codmon,$servicio);
            
            return $this->renderAjax('consumos_por_servicio_droga', [
                'dataProvider' => $dataProvider,
                'id'=>$_POST['expandRowKey'],
            ]);
        } else {
            return '<div class="alert alert-danger">No existe información!</div>';
        }
    }

      public function actionConsumo_por_servicio_clase()
    {
        $searchModel = new ConsumosFiltro();
              
        $dataProvider = $searchModel->buscar_por_servicio_clase(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_por_servicio_clase', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionConsumo_por_vales_planillas()
    {
        $searchModel = new ConsumosFiltro();
              
        $dataProvider = $searchModel->buscar_por_vales_planillas(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_por_vales_planillas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionStock_monodrogas_sala()
    {
        $searchModel = new ConsumosFiltro();
        $searchModel->scenario = 'stock_sala';
        $dataProvider = $searchModel->buscar_stock_monodrogas_sala(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
        
        $movs = $dataProvider->query->all();
        
        $codmon = '';
        $lista_existencias=[];
        foreach ($movs as $key => $value) {
            
            if ($codmon!=$value['codmon'])
             {
                $codmon = $value['codmon'];
                $existencia = 0;

                 if (isset($searchModel->periodo_inicio) && $searchModel->periodo_inicio!='') {
                    $nuevafecha = strtotime ( '-1 day' , strtotime ( $searchModel->periodo_inicio ) ) ;
                    $fecha_inicio_format = date ( 'd-m-Y' , $nuevafecha );
                    $fila_existencia['destinatario'] = 'EXISTENCIA AL '.$fecha_inicio_format;
                    $existencia = $searchModel->existencia_sala(Yii::$app->request->queryParams,$codmon);
                }else{
                     $fila_existencia['destinatario'] = 'EXISTENCIA INICIAL';
                }

                $fila_existencia['fecha'] = '';
                $fila_existencia['hora'] = '';
                $fila_existencia['cantidad_recibida'] = '';
                $fila_existencia['cantidad_entregada'] = '';
                $fila_existencia['existencia'] = $existencia;
                $fila_existencia['nombre'] = $value['nombre'];

                $lista_existencias[] =$fila_existencia;        
            }

            $existencia = $existencia - $value['cantidad_entregada'] + $value['cantidad_recibida'];
            $value['existencia'] = $existencia;
            $lista_existencias[] = $value;


          
        }

        
        $dataProvider =  new ArrayDataProvider([
            'allModels' => $lista_existencias,
           
        ]);




        return $this->render('stock_monodrogas_sala', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionTechos()
    {
        $searchModel = new ConsumosFiltro();
              
        $dataProvider = $searchModel->buscar_techos(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
        }else{
            $filtro = true;
        }
      
        return $this->render('techos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionConsumos_paciente_por_ud()
    {
        $searchModel = new ConsumosPacienteFiltro();
              
        $dataProvider = $searchModel->buscar_por_ud(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_paciente_por_ud', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionDetallePacienteUd() {
        if (isset($_POST['expandRowKey'])) {
            $searchModel = new ConsumosPacienteFiltro();
            $searchModel->load(Yii::$app->request->queryParams);
            $codmon=substr($_POST['expandRowKey'], -4);
            $unidiag=substr($_POST['expandRowKey'], 0, 3);
            $dataProvider = $searchModel->buscar_por_ud_droga($unidiag,$codmon);
            
            return $this->renderAjax('consumos_paciente_por_ud_droga', [
                'dataProvider' => $dataProvider,
                'id'=>$_POST['expandRowKey'],
                'codmon'=>$codmon,
                'unidiag'=>$unidiag,
            ]);
        } else {
            return '<div class="alert alert-danger">No existe información!</div>';
        }
    }

     public function actionConsumos_paciente()
    {
        $searchModel = new ConsumosPacienteFiltro();
         $searchModel->scenario = 'consumos_paciente';
              
        $dataProvider = $searchModel->buscar_paciente(Yii::$app->request->queryParams);

        // echo "<pre>";
        // print_r($query->all()[0]['valefars']);
        // echo "</pre>";
        // die();
        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_paciente', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

     public function actionConsumos_paciente_servicio_cantped()
    {
        $searchModel = new ConsumosPacienteFiltro();
              
        $dataProvider = $searchModel->buscar_servicio_cantped(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_paciente_servicio_cantped', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

     public function actionConsumos_paciente_servicio_ud_at()
    {
        $searchModel = new ConsumosPacienteFiltro();
              
        $dataProvider = $searchModel->buscar_por_servicio_ud_at(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_paciente_servicio_ud_at', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

     public function actionConsumos_paciente_ambu_servicio()
    {
        $searchModel = new ConsumosPacienteFiltro();
              
        $dataProvider = $searchModel->buscar_por_paciente_ambu_servicio(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_paciente_ambu_servicio', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

      public function actionConsumos_paciente_ambu_smu()
    {
        $searchModel = new ConsumosPacienteFiltro();
              
        $dataProvider = $searchModel->buscar_por_ambu_smu(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_paciente_ambu_smu', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

     public function actionConsumos_paciente_ambu_valorizado()
    {
        $searchModel = new ConsumosVentanillaFiltro();
              
        $dataProvider = $searchModel->buscar_por_ambu_valorizado(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_paciente_ambu_valorizado', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

      public function actionConsumos_paciente_ambu_ooss()
    {
        $searchModel = new ConsumosVentanillaFiltro();
              
        $dataProvider = $searchModel->buscar_por_ambu_ooss(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_paciente_ambu_ooss', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionConsumos_ventanilla_demanda()
    {
        $searchModel = new ConsumosVentanillaFiltro();
              
        $dataProvider = $searchModel->buscar_por_demanda(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_ventanilla_demanda', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

  
    public function actionDetalleDrogaDemanda() {
        if (isset($_POST['expandRowKey'])) {
            $searchModel = new ConsumosVentanillaFiltro();
            $searchModel->load(Yii::$app->request->queryParams);
            $dataProvider = $searchModel->buscar_por_demanda_droga($_POST['expandRowKey']);
            
            return $this->renderAjax('consumos_ventanilla_demanda_droga', [
                'dataProvider' => $dataProvider,
                'id'=>$_POST['expandRowKey'],
            ]);
        } else {
            return '<div class="alert alert-danger">No existe información!</div>';
        }
    }

    public function actionConsumos_ventanilla_unidad()
    {
        $searchModel = new ConsumosVentanillaFiltro();
              
        $dataProvider = $searchModel->buscar_por_ventanilla_unidad(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('consumos_ventanilla_unidad', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }


   public function actionLibro_psicofarmacos()
    {
        $searchModel = new ConsumosPacienteFiltro();
              
        $dataProvider = $searchModel->buscar_por_ud(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('libro_psicofarmacos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    

    

    public function actionReposicion_prevision()
    {
        $searchModel = new ConsumosPacienteFiltro();
              
        $dataProvider = $searchModel->buscar_por_ud(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('reposicion_prevision', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionAlarmas_estaticas()
    {
        $searchModel = new ConsumosFiltro();
              
        $dataProvider = $searchModel->buscar_alarmas_estaticas(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('alarmas_estaticas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }

    public function actionAlarmas_dinamicas()
    {
        $searchModel = new ConsumosFiltro();
              
        $dataProvider = $searchModel->buscar_alarmas_dinamicas(Yii::$app->request->queryParams);

        if  ($searchModel->load(Yii::$app->request->get())){
            $filtro = false;
          
        }else{
            $filtro = true;
        }
      
        return $this->render('alarmas_dinamicas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
    }
        
}   

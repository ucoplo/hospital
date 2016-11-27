<?php

namespace deposito_central\controllers;

use Yii;

use deposito_central\models\FiltroIngresos;
use deposito_central\models\FiltroCardex_articulos;
use deposito_central\models\FiltroArticulos;
use deposito_central\models\FiltroAbc;
use deposito_central\models\FiltroUltimaSalida;
use deposito_central\models\FiltroConsumos;
use deposito_central\models\FiltroPerdidas;
use deposito_central\models\FiltroDevoluciones;
use deposito_central\models\FiltroVencimientos;
use deposito_central\models\FiltroReposicion;

use deposito_central\models\Movimientos_diarios;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider ;
use kartik\grid\GridView;
use yii\filters\AccessControl;

use yii\db\Query;
use yii\helpers\Json;
use yii\web\Response;
use yii\widgets\ActiveForm;
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
                'only'=>['articulos'],
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

     public function actionBuscarArticulos($q = null,$deposito = null) {
        $limit = 10;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        //$deposito = Yii::$app->request->get('deposito');
        
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select(['AG_CODIGO as id', 'CONCAT("[",AG_CODIGO,"] ",AG_NOMBRE) AS text'])
                ->from('artic_gral')
                ->orderBy('AG_NOMBRE')
                ->limit($limit);

            if ($deposito != null) {
                $query->andWhere(['=','AG_DEPOSITO', $deposito]);
            }

            // $depositos_habilitados = Yii::$app->params['depositos_central'];
            // $query->andWhere(['IN','AG_DEPOSITO', $depositos_habilitados]);

            $words = explode(' ', $q);
            foreach ($words as $word) {
                $query->andWhere('AG_NOMBRE LIKE "%' . $word .'%" OR AG_CODIGO LIKE "%' . $word .'%"');
            }

            //$query->orWhere(['like', 'AG_CODIGO', $q])->andWhere(['AG_DEPOSITO'=> $deposito]);
            

            $command = $query->createCommand();
            $practicas = $command->queryAll();
            $cantidad = $query->count();

            $out['results'] = array_values($practicas);
            if ($cantidad > $limit)
                    array_unshift($out['results'],['id'=>null,'text'=>"Mostrando $limit de $cantidad resultados"]);
        }
        
        return $out;
    
    }

    public function actionBuscarProveedores($q = null/*,$deposito = null*/) {
        $limit = 10;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
                
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select(['PR_CODIGO as id', 'CONCAT("[",PR_CODIGO,"] ",PR_RAZONSOC) AS text'])
                ->from('proveedores')
                ->orderBy('PR_RAZONSOC')
                ->limit($limit);
           
            $words = explode(' ', $q);
            foreach ($words as $word) {
                $query->andWhere('PR_RAZONSOC LIKE "%' . $word .'%" OR PR_CODIGO LIKE "%' . $word .'%"');
            }

            $command = $query->createCommand();
            $proveedores = $command->queryAll();
            $cantidad = $query->count();

            $out['results'] = array_values($proveedores);
            if ($cantidad > $limit)
                    array_unshift($out['results'],['id'=>null,'text'=>"Mostrando $limit de $cantidad resultados"]);
        }
        
        return $out;
    
    }
    public function actionIngresos()
    {
        $searchModel = new FiltroIngresos();
        $searchModel->agrupado = 'A';
        $dataProvider = $searchModel->buscar(Yii::$app->request->post());
        $dataProvider->sort = false;

        return $this->render('ingresos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }

    public function actionCardex_articulos()
    {
        $searchModel = new FiltroCardex_articulos();
        $dataProvider = $searchModel->buscar(Yii::$app->request->post());
        $dataProvider->sort = false;
              
        $movs = $dataProvider->query->all();
     
        $fila_existencia = new Movimientos_diarios();
        $fila_existencia->load(Yii::$app->request->queryParams);
        
        if (isset($searchModel->fecha_inicio) && $searchModel->fecha_inicio!='') {
            $nuevafecha = strtotime ( '-1 day' , strtotime ( $searchModel->fecha_inicio ) ) ;
            $fecha_inicio_format = date ( 'd-m-Y' , $nuevafecha );
            $fila_existencia->concepto = 'EXISTENCIA AL '.$fecha_inicio_format;
        }else{
             $fila_existencia->concepto = 'EXISTENCIA INICIAL';
        }

        $existencia = $searchModel->existencia();
        $fila_existencia->DM_FECHA = '';
        $fila_existencia->DM_CODART = $searchModel->articulo_descripcion;
        $fila_existencia->entrada = '';
        $fila_existencia->salida = '';
        $fila_existencia->existencia = $existencia;

        $renglon_existencia[] =$fila_existencia;

        foreach ($movs as $key => $value) {
            $value->concepto = $value->DM_CODMOV.' - '.$value->codigo->DM_NOM;
            $value->DM_CODART = $searchModel->articulo_descripcion;
            if ($value->codigo->DM_SIGNO<0){
                $value->salida = $value->DM_CANT;
                $value->entrada = '';
                $existencia -= $value->salida;
                $value->existencia = $existencia;
            }
            else{
                $value->entrada = $value->DM_CANT;
                 $value->salida = '';
                 $existencia += $value->entrada;
                $value->existencia = $existencia;
            }
        }

        $movs = array_merge($renglon_existencia,$movs);
        $dataProvider =  new ArrayDataProvider([
            'allModels' => $movs,
           'pagination' => false,
        ]);
        $request = Yii::$app->request->get();
       if  ($searchModel->load(Yii::$app->request->post()) || isset($request['page'])){
            $filtro = false;
        }else{
            $filtro = true;
        }

        return $this->render('cardex_articulos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => $filtro,
        ]);
     
    }
     public function actionValidateFiltro()
    {
        $model = new FiltroCardex_articulos();
        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
     public function actionCardex_lotes()
    {
        $searchModel = new FiltroCardex_articulos();
          
        $dataProvider = $searchModel->buscar_lotes(Yii::$app->request->post());
       
        $movs = $dataProvider->query->all();
       
        $lote = '';
        $movimientos_mostrar = [];
        $existencia = 0;
        foreach ($movs as $key => $value) {
            $value->concepto = $value->DM_CODMOV.' - '.$value->codigo->DM_NOM;
            $value->DM_CODART = "[$searchModel->articulo]-".$searchModel->articulo_descripcion;

            if ($lote!=$value->DM_FECVTO){
                $lote = $value->DM_FECVTO;

                $fila_existencia = new Movimientos_diarios();
                $fila_existencia->load(Yii::$app->request->queryParams);
                
                if (isset($searchModel->fecha_inicio) && $searchModel->fecha_inicio!='') {
                    $nuevafecha = strtotime ( '-1 day' , strtotime ( $searchModel->fecha_inicio ) ) ;
                    $fecha_inicio_format = date ( 'd-m-Y' , $nuevafecha );
                    $fila_existencia->concepto = 'EXISTENCIA AL '.$fecha_inicio_format;
                }else{
                     $fila_existencia->concepto = 'EXISTENCIA INICIAL';
                }

                $existencia = $searchModel->existencia($value->DM_FECVTO);
                $fila_existencia->DM_FECHA = '';
                
                $fila_existencia->entrada = '';
                $fila_existencia->salida = '';
                $fila_existencia->existencia = $existencia;
                $fila_existencia->DM_CODART = "[$searchModel->articulo]-".$searchModel->articulo_descripcion;
                $fila_existencia->DM_FECVTO = $lote;

                $movimientos_mostrar[] =$fila_existencia;
            }

            if ($value->codigo->DM_SIGNO<0){
                $value->salida = $value->DM_CANT;
                $value->entrada = ''; 
                $existencia -= $value->salida;
                $value->existencia = $existencia;
            }
            else{
                $value->entrada = $value->DM_CANT;
                 $value->salida = '';
                 $existencia += $value->entrada;
                $value->existencia = $existencia;
            }

            

            $movimientos_mostrar[] = $value;
        }

        
        $dataProvider =  new ArrayDataProvider([
            'allModels' => $movimientos_mostrar,
            'pagination' => false,
        ]);


        return $this->render('cardex_lotes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }
    
     public function actionStock()
    {
        $searchModel = new FiltroArticulos();
        $searchModel->activo = '';
        $dataProvider = $searchModel->buscar(Yii::$app->request->post());
        $dataProvider->pagination = false;
      
        return $this->render('stock', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }

     public function actionAbc()
    {
        $searchModel = new FiltroAbc();
        $searchModel->activos = '';
        
        $dataProvider = $searchModel->abc(Yii::$app->request->post());
        
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
            'pagination' => false,
        ]);
      
        return $this->render('abc', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
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
        $searchModel = new FiltroAbc();
        $searchModel->activos = '';
        
        $dataProvider = $searchModel->abc_servicio(Yii::$app->request->post());
       
        
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
            'pagination' => false,
        ]);

        return $this->render('abc_servicio', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }

    public function actionUltima_salida()
    {
        $searchModel = new FiltroUltimaSalida();
        
        $searchModel->nombre1 = "Activo";
        $searchModel->limite1 = "60";
        $searchModel->nombre2 = "Dormido";
        $searchModel->limite2 = "120";
        $searchModel->nombre3 = "Obsoleto";
        $searchModel->limite3 = "180";
        $searchModel->nombre4 = "Muerto";
        $searchModel->limite4 = "365";

        $dataProvider = $searchModel->buscar(Yii::$app->request->post());
        $dataProvider->pagination = false;
        return $this->render('ultima_salida', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }

    public function actionIndices()
    {
        $searchModel = new FiltroConsumos();
        $searchModel->scenario = 'indices';
        $dataProvider = $searchModel->buscar_indices(Yii::$app->request->post());

        if  ($searchModel->load(Yii::$app->request->post())){
            if (!$searchModel->validate()){
                return $this->render('indices', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'filtro' => true,
                ]);
            }
            $drogas = $dataProvider->query->all();
           
            for ($key=0; $key < count($drogas); $key++) { 
               $existencia_promedio = $searchModel->existencia_promedio($drogas[$key]['codart']);
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

            array_multisort($sortArray["indice"],SORT_DESC,$drogas); 
            //Fin ordenamiento
            
            $dataProvider =  new ArrayDataProvider([
                'allModels' => $drogas,
                'pagination' => false,
            ]);
        }
      
        return $this->render('indices', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }

    public function actionSalida_media()
    {
        $searchModel = new FiltroConsumos();
        $searchModel->scenario = 'salida_media';
        $salida_media = $searchModel->buscar_salida_media(Yii::$app->request->post());
        $salida_media->pagination = false;

        return $this->render('salida_media', [
            'searchModel' => $searchModel,
            'salida_media' => $salida_media,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }

    public function actionAlarmas_estaticas()
    {
        $searchModel = new FiltroConsumos();
              
        $dataProvider = $searchModel->buscar_alarmas_estaticas(Yii::$app->request->post());
        $dataProvider->pagination = false;
             
        return $this->render('alarmas_estaticas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }

    public function actionConsumo_por_articulo()
    {
        $searchModel = new FiltroConsumos();
              
        $dataProvider = $searchModel->buscar_por_articulo(Yii::$app->request->post());
        $dataProvider->pagination = false;

        return $this->render('consumos_por_articulo', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }

    public function actionDetalleArticulo() {
        if (isset($_POST['expandRowKey'])) {
            $searchModel = new FiltroConsumos();
            $searchModel->load(Yii::$app->request->queryParams);
            $dataProvider = $searchModel->buscar_por_articulo_detalle($_POST['expandRowKey']);
            $dataProvider->pagination = false;
            return $this->renderAjax('consumos_por_articulo_detalle', [
                'dataProvider' => $dataProvider,
                'id'=>$_POST['expandRowKey'],
            ]);
        } else {
            return '<div class="alert alert-danger">No existe información!</div>';
        }
    }

      public function actionConsumo_por_servicio()
    {   
         $searchModel = new FiltroConsumos();
              
        $dataProvider = $searchModel->buscar_por_servicio(Yii::$app->request->post());
        $dataProvider->pagination = false;

        return $this->render('consumos_por_servicio', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);

    }

    public function actionDetalleArticuloServicio() {
        if (isset($_POST['expandRowKey'])) {
            $searchModel = new FiltroConsumos();
            $searchModel->load(Yii::$app->request->queryParams);
            $codart=substr($_POST['expandRowKey'], -4);
            $servicio=substr($_POST['expandRowKey'], 0, 3);
            $dataProvider = $searchModel->buscar_por_servicio_detalle($codart,$servicio);
            
            $dataProvider->pagination = false;

            return $this->renderAjax('consumos_por_servicio_detalle', [
                'dataProvider' => $dataProvider,
                'id'=>$_POST['expandRowKey'],
            ]);
        } else {
            return '<div class="alert alert-danger">No existe información!</div>';
        }
    }

     public function actionConsumo_por_servicio_clase()
    {
        $searchModel = new FiltroConsumos();
              
        $dataProvider = $searchModel->buscar_por_servicio_clase(Yii::$app->request->post());
        $dataProvider->pagination = false;

        return $this->render('consumos_por_servicio_clase', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }

     public function actionPerdidas()
    {
        $searchModel = new FiltroPerdidas();
        
        $dataProvider = $searchModel->buscar(Yii::$app->request->post());
        $dataProvider->pagination = false;
     
        return $this->render('perdidas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }

    public function actionDevoluciones()
    {
        $searchModel = new FiltroDevoluciones();
        $searchModel->planillas = 1;
        $searchModel->sobrante = 1;
        
        $dataProvider = $searchModel->buscar(Yii::$app->request->post());
        $dataProvider->pagination = false;

        return $this->render('devoluciones', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),
        ]);
    }

    public function actionArticulos_vencimientos()
    {
        $searchModel = new FiltroVencimientos();
               
        $dataProvider = $searchModel->buscar(Yii::$app->request->post());
        $dataProvider->pagination = false;
             
        return $this->render('vencimientos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),        
        ]);
    }

     public function actionArticulos_vencidos()
    {
        $searchModel = new FiltroVencimientos();
               
        $dataProvider = $searchModel->buscarVencidos(Yii::$app->request->post());
        $dataProvider->pagination = false;

        return $this->render('articulos_vencidos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),  
        ]);
    }

     public function actionArticulos_por_vencer()
    {
        $searchModel = new FiltroVencimientos();
               
        $dataProvider = $searchModel->buscarPorVencer(Yii::$app->request->post());
        $vencimientos = $dataProvider->query->all();

        $lotes_por_vencer = [];
        foreach ($vencimientos as $key => $venc) {
            $consumo_diario = $searchModel->consumo_medio_diario($venc->DT_CODART);

            $venc->consumo_medio = $consumo_diario;
            
            $segundos=strtotime($venc->DT_FECVEN) - strtotime('now');
            $dias=intval($segundos/60/60/24);
            
           
            $saldo_futuro = $venc->DT_SALDO - ($consumo_diario*$dias);
            
            //Si no se consumira el lote y el consumo medio es mayor o igual a  cero se vencerá
            if (($saldo_futuro>0) && ($consumo_diario>=0)){
                $lotes_por_vencer[] = $venc;
            }
        }
        $dataProvider =  new ArrayDataProvider([
            'allModels' => $lotes_por_vencer,
        ]);
        $dataProvider->pagination = false;

        return $this->render('articulos_por_vencer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),  
        ]);
    }

     public function actionPedidos_pendientes()
    {
        $searchModel = new FiltroReposicion();
        $searchModel->scenario = 'pendientes';

        $dataProvider = $searchModel->buscar_pendientes_entrega(Yii::$app->request->post());
        $dataProvider->pagination = false;

        return $this->render('pedidos_pendientes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),  
        ]);
    }

     public function actionPrevision()
    {
        $searchModel = new FiltroReposicion();
        $searchModel->scenario = 'prevision';
        $dataProvider = $searchModel->buscar_prevision(Yii::$app->request->post());

        $dataProvider->pagination = false;

        return $this->render('prevision', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),  
        ]);
    }

     public function actionSalida_valorizada()
    {
        $searchModel = new FiltroReposicion();
        $searchModel->scenario = 'salida_valorizada';
        $dataProvider = $searchModel->buscar_salida_valorizada(Yii::$app->request->post());
        $dataProvider->pagination = false;
        
        return $this->render('salida_valorizada', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filtro' => !$searchModel->load(Yii::$app->request->post()),  
        ]);
    }
}   

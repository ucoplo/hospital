<?php

namespace deposito_central\controllers;

use Yii;
use deposito_central\models\Pedido_adquisicion;
use deposito_central\models\Pedido_adquisicion_renglones;
use deposito_central\models\Pedido_adquisicionSearch;
use deposito_central\models\ArticGral;
use deposito_central\models\Clases;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\ErrorException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider ;
use yii\db\Query;

/**
 * Pedido_adquisicionController implements the CRUD actions for Pedido_adquisicion model.
 */
class Pedido_adquisicionController extends Controller
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
                    'rules'=>[
                        [
                            'allow'=>true,
                            'roles'=> ['@'],
                            'actions' => ['index','view','create','generar_pedido'],
                            'matchCallback' => 
                                function ($rule, $action) {
                                    return Yii::$app->user->identity->habilitado($action);
                                }
                        ],
                         [
                            'allow'=>true,
                            'roles'=> ['@'],
                            'actions' => ['validate-asociar-pedido','buscar-articulos',
                                        'datos_nuevo_renglon'],
                            
                        ]
                    ]
                ]
            ];
        }
    

    /**
     * Lists all Pedido_adquisicion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Pedido_adquisicionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pedido_adquisicion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pedido_adquisicion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $search = new Pedido_adquisicionSearch();

        if ($search->load(Yii::$app->request->post())) {
            $search->load(Yii::$app->request->post());
            if ($search->validate()){
                $model = new Pedido_adquisicion();

                $model->PE_DEPOSITO = $search->PE_DEPOSITO;
                $model->PE_REFERENCIA = $search->PE_REFERENCIA;
                $model->PE_ARTDES = $search->PE_ARTDES;
                $model->PE_ARTHAS = $search->PE_ARTHAS;
                $model->PE_CLASES = $search->PE_CLASES;
                $model->PE_ACTIVOS = $search->PE_ACTIVOS;
                $model->PE_INACTIVOS = $search->PE_INACTIVOS;
                $model->PE_EXISACT = $search->PE_EXISACT;
                $model->PE_PEDPEND = $search->PE_PEDPEND;
                $model->PE_PONDHIS = $search->PE_PONDHIS;
                $model->PE_PONDPUN = $search->PE_PONDPUN;
                $model->PE_DIASABC = $search->PE_DIASABC;
                $model->PE_DIASPREVIS = $search->PE_DIASPREVIS;
                $model->PE_DIASDEMORA = $search->PE_DIASDEMORA;
                $model->CLASE_A = $search->CLASE_A;
                $model->CLASE_B = $search->CLASE_B;
                $model->CLASE_C = $search->CLASE_C;

                $rows = (new \yii\db\Query())
                    ->select([' max(PE_NUM) + 1 as next_id'])
                    ->from('ped_adq')
                    ->one();
                if (isset($rows) && isset($rows['next_id'])){
                   
                    $model->PE_NUM=$rows['next_id'];
                }
                else{
                    $model->PE_NUM=1;
                }

                $model->PE_FECHA=date('Y-m-d');
                $model->PE_HORA=date('H:i');
                $model->PE_COSTO=0;
                $model->PE_CLASABC = '';
                $model->PE_CLASABC .= ($search->CLASE_A)?'A':'';
                $model->PE_CLASABC .= ($search->CLASE_B)?'B':'';
                $model->PE_CLASABC .= ($search->CLASE_C)?'C':'';
                
                $this->generar_renglones($model);
               
                return $this->render('generar_pedido', [
                    'model' => $model,
                ]);
            }
            else{
                
                return $this->render('create', [
                    'model' => $search,
                ]);                
            }
        } else {
            $search->PE_DEPOSITO = Yii::$app->params['depositos_central'][0];
            $search->PE_ACTIVOS = 1;
            $search->PE_INACTIVOS = 1;
            $search->PE_EXISACT = 1;
            $search->PE_PEDPEND = 1;
            $search->PE_PONDHIS = 30;
            $search->PE_PONDPUN = 70;
            $search->PE_DIASABC = 90;
            $search->PE_DIASPREVIS = 90;
            $search->PE_DIASDEMORA = 30;
           

            return $this->render('create', [
                'model' => $search,
            ]);
        }
    }

     private function guardar_renglones($model)
    {
      try{
        $num_renglon = 1;
        foreach ($model->renglones as $key => $obj) {
          
            //Se guarda cada renglón del pedido
            $renglon = new Pedido_adquisicion_renglones();

            $renglon->PE_NUM = $model->PE_NUM;
            $renglon->PE_NRORENG = $num_renglon;
            $renglon->PE_DEPOSITO = $model->PE_DEPOSITO;
            $renglon->PE_CODART = $obj['PE_CODART'];
            $renglon->PE_CLASE = $obj['PE_CLASE'];
            $renglon->PE_CANT = $obj['PE_CANTPED']; 
            $renglon->PE_PRECIO = $obj['precio']; 
            $renglon->PE_REDONDEO = 0;
            $renglon->PE_CANTPED = $obj['PE_CANTPED']; 
            $renglon->PE_SUGERIDO = $obj['cantidad_sugerida']; 
            $renglon->PE_EXISTENCIA = $obj['existencia']; 
            $renglon->PE_PENDIENTE = $obj['pendiente_entrega']; 
            $renglon->PE_CONSUMO = ($obj['cons_puntual']*$obj['cons_historico'])/2; 
            
            if (!$renglon->save()){
              $mensaje = ""; 
              foreach ($renglon->getFirstErrors() as $key => $value) {
                $mensaje .= "rr\\n\\r $value";
              }
              
              throw new ErrorException($mensaje);
            }
            $num_renglon++;
        }
      }
      catch (\Exception $e) {
          throw $e;
      }
    }


     public function actionGenerar_pedido()
    {
        $model = new Pedido_adquisicion();
        //$model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate() ){   
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                try {
                    //Se guarda encabezado del Pedido

                    if (isset($model->PE_CLASES) && !empty($model->PE_CLASES)){
                        $model->PE_CLASES = implode(",",$model->PE_CLASES);
                    }
                    if ($model->save()){
                        $this->guardar_renglones($model);
                                                
                      }else{
                         $mensaje = ""; 
                        foreach ($model->getFirstErrors() as $key => $value) {
                          $mensaje .= "$value \\n\\r";
                        }
                        
                        throw new ErrorException($mensaje);
                      }

                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('exito_deposito_central', 'Pedido de Adquisición creado con éxito.');
                    return $this->redirect(['view', 'id' => $model->PE_NUM]);
                    
                }
                catch (\Exception $e) {
                    $transaction->rollBack();
                    
                    Yii::$app->getSession()->setFlash('error_deposito_central', $e->getMessage());
                    
                    return $this->render('generar_pedido', [
                    'model' => $model,
                    ]);
                }
            } else {
                
                return $this->render('generar_pedido', [
                'model' => $model,
                ]);
            }
            
        } else {
            
            return $this->render('generar_pedido', [
                'model' => $model,
            ]);
        }
      }

    /**
     * Updates an existing Pedido_adquisicion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->PE_NUM]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Pedido_adquisicion model.
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
     * Finds the Pedido_adquisicion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pedido_adquisicion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pedido_adquisicion::findOne($id)) !== null) {
            
            if (isset($model->PE_CLASES) && !empty($model->PE_CLASES)){ 
                $clases = explode(",",$model->PE_CLASES);
                $model->PE_CLASES = "";
               
                foreach ($clases as $key => $value) {
                   $model->PE_CLASES .= Clases::findOne($value)->CL_NOM.', ';
                }
            }
            $searchModel = new Pedido_adquisicion_renglones();
            $model->renglones =  $searchModel->get_renglones($model->PE_NUM);

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    private function articulos_reponer($model){

        $query = ArticGral::find();

        $query->where(['AG_DEPOSITO'=>$model->PE_DEPOSITO]);

        if (isset($model->PE_ACTIVOS) && $model->PE_ACTIVOS) {
            if (isset($model->PE_INACTIVOS) && !$model->PE_INACTIVOS) {
                $query->andFilterWhere(['AG_ACTIVO'=>'T']);
            }
        }else{
            if (isset($model->PE_INACTIVOS) && $model->PE_INACTIVOS) {
                $query->andFilterWhere(['AG_ACTIVO'=>'F']);
            }
        }

        $query->andFilterWhere(['IN', 'AG_CODCLA', $model->PE_CLASES]);

        if (isset($model->PE_ARTDES) && $model->PE_ARTDES!='') {
            $codigo_numero = intval($model->PE_ARTDES);
            $query->andFilterWhere(['>=', '(`AG_CODIGO` * 1)', $codigo_numero]);
        }

        if (isset($model->PE_ARTHAS) && $model->PE_ARTHAS!='') {
             $codigo_numero = intval($model->PE_ARTHAS);
             $query->andFilterWhere(['<=', '(`AG_CODIGO` * 1)', $codigo_numero]);
        }

        if ($model->CLASE_A || $model->CLASE_B || $model->CLASE_C){

            $movs = $model->abc();

            $total_consumo = 0;
            $articulos_abc = array();

            foreach ($movs as $key => $mov) {
                $total_consumo += $mov->consumo_valor;
            }
            $porc_acumulado = 0;
            foreach ($movs as $key => $mov) {
                
                $mov->porc_abc = ($mov->consumo_valor*100)/$total_consumo;

                $porc_acumulado += $mov->porc_abc;
                $mov->porc_abc = $porc_acumulado;
                //echo $mov->DM_CODART.'-'.$mov->consumo_valor.'--'.$porc_acumulado;echo "<br>"; 

                if ($porc_acumulado>=95){
                    if ($model->CLASE_C){
                        $articulos_abc[] = $mov->DM_CODART;
                    }
                }elseif ($porc_acumulado>=80) {
                    if ($model->CLASE_B){
                        $articulos_abc[] = $mov->DM_CODART;
                    }
                }elseif ($model->CLASE_A){
                        $articulos_abc[] = $mov->DM_CODART;
                }
            }
            
            if (count($articulos_abc)==0){
                $articulos_abc = ['articulo_vacio'];
            }

            $query->andFilterWhere(['IN', 'AG_CODIGO',  $articulos_abc]);

        }
        return $query->all();
    }


    protected function generar_renglones($model)
    {
        //Selecciono los articulos a reponer segun filtro
        $articulos_reponer = $this->articulos_reponer($model);

        $renglones = array();
        $ph = $model->PE_PONDHIS; //porcentaje ponderacion historico
        $pp = $model->PE_PONDPUN; //porcentaje ponderacion consumo puntual
        $dp = $model->PE_DIASPREVIS; //dias de prevision
        $Dt = $model->PE_DIASDEMORA; //dias de demora tramite

        $costo_total = 0;
        foreach ($articulos_reponer as $key => $articulo) {
            //existencia actual
            $Ei = 0;
            if (isset($model->PE_EXISACT) && $model->PE_EXISACT){
                $Ei = (isset($articulo->AG_STACDEP))?$articulo->AG_STACDEP:0;
            }

            //cantidad pedida pendiente de entrega
            $Pi = 0;
            if (isset($model->PE_PEDPEND) && $model->PE_PEDPEND){
                $Pi = $articulo->pendiente_entrega();
            }
                       
            //consumo promedio diario histórico (date() - 365)
            $Hi = $articulo->consumo_promedio_diario_historico();

            //consumo promedio diario del período reciente (date() - Dp)
            $Ci = $articulo->consumo_promedio_diario_puntual($dp);

            //lo que estimo voy a consumir a lo largo de los días de previsión
            //con un consumo promedio ponderado 
            $ESi = $dp * ((($ph/100)*$Hi)+(($pp/100)*$Ci))/2;
            
            //existencia que habrá en el momento de que ingresen los artículos de esta reposición
            $EEi = $Ei + $Pi - ($Dt * $Ci);
      
            //cantidad sugerida articulo redondeado hacia arriba
            $cant_artic =  ceil($ESi - $EEi);

            if ($cant_artic>0){
                $renglon = new Pedido_adquisicion_renglones();
                $renglon->PE_CODART = $articulo->AG_CODIGO;
                $renglon->PE_DEPOSITO = $articulo->AG_DEPOSITO;
                $renglon->descripcion = $articulo->AG_NOMBRE;
                $renglon->clase = $articulo->clase->CL_NOM;
                $renglon->PE_CLASE = $articulo->AG_CODCLA;
                $renglon->precio =  number_format($articulo->AG_PRECIO,2);
                $renglon->PE_CANTPED = $cant_artic;
                $renglon->cantidad_sugerida = $cant_artic;
                $renglon->cons_puntual = round($Ci, 2);
                $renglon->cons_historico = round($Hi, 2);
                $renglon->existencia = round($Ei, 2);
                $renglon->pendiente_entrega = round($Pi, 2);
                $renglon->cantidad_pack = (isset($articulo->AG_UNIENV)) ? $articulo->AG_UNIENV : 1 ;
                $renglones[] = $renglon;

                $costo_total +=  ($articulo->AG_PRECIO*$cant_artic);
            }
        }

        $model->renglones = $renglones;
        $model->PE_COSTO = $costo_total;
    }

    public function actionBuscarArticulos($q = null/*,$deposito = null*/) {
        $limit = 10;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $deposito = Yii::$app->request->get('deposito');
        
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

    public function actionDatos_nuevo_renglon()
    {

        $codart= $_POST['codart'];
        $deposito=$_POST['deposito'];
        $existencia_actual=$_POST['existencia_actual'];
        $cant_pend_entrega=$_POST['cant_pend_entrega'];
        $ph=$_POST['cons_historico'];
        $pp=$_POST['cons_puntual'];
        $dp=$_POST['dias_prevision'];
        $Dt=$_POST['dias_tramite'];

        $articulo = ArticGral::findOne(['AG_CODIGO'=>$codart,'AG_DEPOSITO'=>$deposito]);

        //existencia actual
        $Ei = 0;
        if (isset($existencia_actual) && $existencia_actual){
            $Ei = (isset($articulo->AG_STACDEP))?$articulo->AG_STACDEP:0;
        }

        //cantidad pedida pendiente de entrega
        $Pi = 0;
        if (isset($cant_pend_entrega) && $cant_pend_entrega){
            $Pi = $articulo->pendiente_entrega();
        }
                   
        //consumo promedio diario histórico (date() - 365)
        $Hi = $articulo->consumo_promedio_diario_historico();

        //consumo promedio diario del período reciente (date() - Dp)
        $Ci = $articulo->consumo_promedio_diario_puntual($dp);

        //lo que estimo voy a consumir a lo largo de los días de previsión
        //con un consumo promedio ponderado 
        $ESi = $dp * ((($ph/100)*$Hi)+(($pp/100)*$Ci))/2;
        
        //existencia que habrá en el momento de que ingresen los artículos de esta reposición
        $EEi = $Ei + $Pi - ($Dt * $Ci);
  
        //cantidad sugerida articulo redondeado hacia arriba
        $cant_artic =  ceil($ESi - $EEi);

        $datos_renglon['clase'] = $articulo->clase->CL_NOM;
        $datos_renglon['PE_CLASE'] = $articulo->AG_CODCLA;
        $datos_renglon['precio'] =  $articulo->AG_PRECIO;
        $datos_renglon['descripcion'] =  $articulo->AG_NOMBRE;
        $datos_renglon['PE_CANTPED'] = 0;
        $datos_renglon['cantidad_sugerida'] = 0;
        $datos_renglon['cons_puntual'] = round($Ci, 2);
        $datos_renglon['cons_historico'] = round($Hi, 2);
        $datos_renglon['existencia'] = round($Ei, 2);
        $datos_renglon['pendiente_entrega'] = round($Pi, 2);
        $datos_renglon['cantidad_pack'] = (isset($articulo->AG_UNIENV)) ? $articulo->AG_UNIENV : 1 ;

        return \yii\helpers\Json::encode($datos_renglon);
        
    }
}

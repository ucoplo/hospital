<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Pedentre;
use farmacia\models\Pedidos_reposicionSearch;
use farmacia\models\PeenMov;
use farmacia\models\Clases;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider ;
use yii\filters\AccessControl;
use yii\db\Query;

/**
 * Pedidos_adquisicionController implements the CRUD actions for Pedentre model.
 */
class Pedidos_reposicionController extends Controller
{   
    public $CodController="009";
     private $referencia;
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
     * Lists all Pedentre models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new Pedidos_reposicionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pedentre model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pedentre model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $model = new Pedentre();
       
        $rows = (new \yii\db\Query())
                ->select([' max(PE_NROPED) + 1 as next_id'])
                ->from('pedentre')
                ->one();
        if (isset($rows) && isset($rows['next_id'])){
           
            $model->PE_NROPED=$rows['next_id'];
        }
        else{
            $model->PE_NROPED=1;
        }

        $model->PE_FECHA=date('Y-m-d');
        $model->PE_HORA = date('H:i:s');

        $searchModel = new PeenMov();
        $renglones =  $searchModel->get_renglones();
        $model->pedido_renglones = "a";

        if ($model->load(Yii::$app->request->post())) {
               
                

                if(isset($_POST['btngenerar'])){
                    
                    $model->incluye_demanda_insatisfecha = $_POST['Pedentre']['incluye_demanda_insatisfecha'];
                    $this->generar_renglones($model);
                    $renglones = $model->pedido_renglones;
          
                    return $this->render('create', [
                        'model' => $model,
                        'renglones' => $renglones
                    ]);
                }

                 if(isset($_POST['btnguardar'])){
                    
                     if (!isset($_POST['renglones'])){
                        
                        $model->pedido_renglones = "";
                        $model->validate();
                        
                        return $this->render('create', [
                            'model' => $model,
                            'renglones' => $renglones,
                        ]);
                     }
                    $connection = \Yii::$app->db;
                    $transaction = $connection->beginTransaction();

                    try {
                    $model->PE_CLASE = implode(",",$model->PE_CLASE);
                    
                   
                                      
                    $model->PE_HORA = date('H:i:s');
                    $model->PE_SERSOL = Yii::$app->params['servicio'];
                    $model->PE_SUPERV = Yii::$app->user->identity->LE_NUMLEGA;

                    if ($model->save()){
                        
                        $renglones = $_POST['renglones'];
                         
                         foreach ($renglones as $key => $obj) {
                            $renglon = new PeenMov();
                            $renglon->PE_NROPED = $model->PE_NROPED;
                            $renglon->PE_NRORENG=$obj['PE_NRORENG'];
                            $renglon->PE_CODMON = $obj['PE_CODMON'];
                            $renglon->PE_CANTPED = $obj['PE_CANTPED'];
                            $renglon->save();
                           
                         }
                     }

                    

                      $transaction->commit();
                    
                      return $this->redirect(['view', 'id' => $model->PE_NROPED]);
                    }
                    catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                  }
    
            } else {

                return $this->render('create', [
                    'model' => $model,
                    'renglones' => $renglones,
                ]);
            }
    }

    
    /**
     * Deletes an existing Pedentre model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pedentre model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Pedentre the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pedentre::findOne($id)) !== null) {
            
             
            $clases = explode(",",$model->PE_CLASE);
            $model->PE_CLASE = "";
            foreach ($clases as $key => $value) {
               $model->PE_CLASE .= Clases::findOne($value)->CL_NOM;
            }

            $searchModel = new PeenMov();
            $model->pedido_renglones =  $searchModel->get_renglones($model->PE_NROPED);

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function generar_renglones($model)
    {
        
        
        $fecha_desde = date('Y-m-d', strtotime("-$model->dias_reponer day"));
        
        
        $connection = \Yii::$app->db;
        
         if (isset($model->incluye_demanda_insatisfecha)){
         
            //Cantidad de Monodrogas Entregadas 
            $sql_entregado = "select codmon, artic_gral.AG_NOMBRE as descripcion,sum(total) as cantidad
                    from
                        (select codmon, sum(cantidad) as total
                    
                          from (SELECT valefar.VA_CODMON as codmon, sum(valefar.VA_CANTID) as cantidad FROM consmed 
                                inner join valefar on valefar.VA_NROVALE=consmed.CM_NROREM
                                where consmed.CM_FECHA >= '".$fecha_desde."' and valefar.VA_DEPOSITO = '".$model->PE_DEPOSITO."'
                                group by valefar.VA_CODMON 
                                
                                union
                                
                                SELECT perdfar.PF_CODMON as codmon, sum(perdfar.PF_CANTID) as cantidad FROM perdidas 
                                inner join perdfar on perdfar.PF_NROREM=perdidas.PE_NROREM
                                where perdidas.PE_FECHA >= '".$fecha_desde."' and perdfar.PF_DEPOSITO = '".$model->PE_DEPOSITO."'
                                group by perdfar.PF_CODMON 
                                
                                union
                                
                                SELECT planfar.PF_CODMON as codmon, sum(planfar.PF_CANTID) as cantidad FROM consme3 
                                inner join planfar on planfar.PF_NROREM=consme3.CM_NROREM
                                where consme3.CM_FECHA >= '".$fecha_desde."' and planfar.PF_DEPOSITO = '".$model->PE_DEPOSITO."'
                                group by planfar.PF_CODMON) as entregas

                          group by codmon
                          
                          union
                          select codmon, sum(cantidad)*-1 as total
                            from (SELECT  devofar.DF_CODMON as codmon, sum(devofar.DF_CANTID) as cantidad 
                                  FROM devoluc 
                                  inner join devofar on devofar.DF_NRODEVOL=devoluc.DE_NRODEVOL
                                  where devoluc.DE_FECHA >= '".$fecha_desde."' and devofar.DF_DEPOSITO = '".$model->PE_DEPOSITO."'
                                  group by devofar.DF_CODMON 
                                                
                                  union
                                
                                  SELECT dev_val.DV_CODMON as codmon, sum(dev_val.DV_CANTID) as cantidad 
                                  FROM devoluc2 
                                  inner join dev_val on dev_val.DV_NRODEVOL=devoluc2.DE_NRODEVOL
                                  where devoluc2.DE_FECHA >= '".$fecha_desde."' and dev_val.DV_DEPOSITO = '".$model->PE_DEPOSITO."'
                                  group by dev_val.DV_CODMON) as devoluciones
                        group by codmon) as total
                   inner join artic_gral on (artic_gral.AG_CODIGO=total.codmon and artic_gral.AG_DEPOSITO='".$model->PE_DEPOSITO."' and artic_gral.AG_CODCLA in ('".implode("','",$model->PE_CLASE)."'))
                   group by codmon, artic_gral.AG_NOMBRE";         

                if ($model->incluye_demanda_insatisfecha=="S"){
                  // Cantidad de pedidos realizados
                  $sql_pedido = "select codmon, artic_gral.AG_NOMBRE as descripcion,sum(cantidad_total) as cantidad
                                 from (select codmon, sum(cantidad) as cantidad_total
                                       from (SELECT vale_enf.VE_FECHA as fecha, vaen_ren.VE_DEPOSITO as coddeposito, vaen_ren.VE_CODMON as codmon, sum(vaen_ren.VE_CANTID) as cantidad 
                                             FROM vale_enf 
                                             inner join vaen_ren on vaen_ren.VE_NUMVALE=vale_enf.VE_NUMVALE
                                             group by vaen_ren.ve_codmon, vale_enf.VE_FECHA, vaen_ren.VE_DEPOSITO
                                            
                                             union
                                                                        
                                             SELECT vale_mon.VM_FECHA as fecha,vamo_ren.VM_DEPOSITO as coddeposito,vamo_ren.VM_CODMON as codmon, sum(vamo_ren.VM_CANTID) as cantidad 
                                             FROM vale_mon 
                                             inner join vamo_ren on vamo_ren.VM_NUMVALE=vale_mon.VM_NUMVALE
                                             group by vamo_ren.vm_codmon,vamo_ren.VM_DEPOSITO,vale_mon.VM_FECHA) as vales 
                                        where fecha >= '".$fecha_desde."' and coddeposito = '".$model->PE_DEPOSITO."'
                                        group by codmon) as totales
                                 inner join artic_gral on (artic_gral.AG_CODIGO=totales.codmon and artic_gral.AG_DEPOSITO='".$model->PE_DEPOSITO."' and artic_gral.AG_CODCLA in ('".implode("','",$model->PE_CLASE)."'))
                                 group by codmon, artic_gral.AG_NOMBRE";

                    

                   //Cantidad de Monodrogas Entregadas + Demanda insatisfecha           
                   $sql = "select case when (entregado.codmon is null) then pedido.codmon else entregado.codmon end as codmon, 
                              case when (entregado.descripcion is null) then pedido.descripcion else entregado.descripcion end as descripcion, 
                              case when (entregado.cantidad is null) then pedido.cantidad else entregado.cantidad end as cantidad
                            from
                              ($sql_entregado)  as entregado
                            right join    ($sql_pedido) as pedido   on (pedido.codmon = entregado.codmon)  
                           union
                           select case when (entregado.codmon is null) then pedido.codmon else entregado.codmon end as codmon, 
                              case when (entregado.descripcion is null) then pedido.descripcion else entregado.descripcion end as descripcion, 
                              entregado.cantidad as demanda
                            from
                              ($sql_entregado)  as entregado
                            left join    ($sql_pedido) as pedido   on (pedido.codmon = entregado.codmon)  

                            ";
                }
               else{
                  $sql = $sql_entregado;
               }

           }

        $comando = $connection->createCommand($sql);
        
        
        $pedidos = $comando->queryAll();

              
        $renglones = array();
        $i = 1;
        foreach ($pedidos as $key => $value) {
            $renglon = new PeenMov();
            $renglon->PE_NROPED = $model->PE_NROPED;
            $renglon->PE_NRORENG=$i;
            $renglon->PE_CODMON = $value['codmon'];
            $renglon->descripcion = $value['descripcion'];
            $renglon->PE_CANTPED = $value['cantidad'];
            $renglones[] = $renglon;
            $i++;
        }
       

        $model->pedido_renglones= new ArrayDataProvider([
            'allModels' => $renglones,
            'key' => 'PE_ID',
        ]);

    }
}

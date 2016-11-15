<?php

namespace deposito_central\controllers;

use Yii;
use deposito_central\models\Techo_articulo;
use deposito_central\models\Techo_articuloSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\helpers\Json;
use deposito_central\models\ArticGral;
/**
 * Techo_articuloController implements the CRUD actions for Techo_articulo model.
 */
class Techo_articuloController extends Controller
{
   public $CodController="008";
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::classname(),
                 'only'=>['index','create','update','delete','view'],
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
     * Lists all Techo_articulo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Techo_articuloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Techo_articulo model.
     * @param string $TA_CODSERV
     * @param string $TA_DEPOSITO
     * @param string $TA_CODART
     * @return mixed
     */
    public function actionView($TA_CODSERV, $TA_DEPOSITO, $TA_CODART)
    {
        return $this->render('view', [
            'model' => $this->findModel($TA_CODSERV, $TA_DEPOSITO, $TA_CODART),
        ]);
    }

    /**
     * Creates a new Techo_articulo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Techo_articulo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'TA_CODSERV' => $model->TA_CODSERV, 'TA_DEPOSITO' => $model->TA_DEPOSITO, 'TA_CODART' => $model->TA_CODART]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Techo_articulo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $TA_CODSERV
     * @param string $TA_DEPOSITO
     * @param string $TA_CODART
     * @return mixed
     */
    public function actionUpdate($TA_CODSERV, $TA_DEPOSITO, $TA_CODART)
    {
        $model = $this->findModel($TA_CODSERV, $TA_DEPOSITO, $TA_CODART);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'TA_CODSERV' => $model->TA_CODSERV, 'TA_DEPOSITO' => $model->TA_DEPOSITO, 'TA_CODART' => $model->TA_CODART]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Techo_articulo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $TA_CODSERV
     * @param string $TA_DEPOSITO
     * @param string $TA_CODART
     * @return mixed
     */
    public function actionDelete($TA_CODSERV, $TA_DEPOSITO, $TA_CODART)
    {
        $this->findModel($TA_CODSERV, $TA_DEPOSITO, $TA_CODART)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Techo_articulo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $TA_CODSERV
     * @param string $TA_DEPOSITO
     * @param string $TA_CODART
     * @return Techo_articulo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($TA_CODSERV, $TA_DEPOSITO, $TA_CODART)
    {
        if (($model = Techo_articulo::findOne(['TA_CODSERV' => $TA_CODSERV, 'TA_DEPOSITO' => $TA_DEPOSITO, 'TA_CODART' => $TA_CODART])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionQuery($q = null) {

        try {
            $query = new Query;
            $query->select(['AG_CODIGO as id', "CONCAT(AG_CODIGO,'-',AG_NOMBRE) as `text`"])->from(ArticGral::tableName());
            $query->where('AG_NOMBRE LIKE "%' . $q .'%" OR AG_CODIGO LIKE "%' . $q .'%"');
            $keywords=explode(' ', $q);
            if(count($keywords)>0) { // caso búsqueda con multiples keywords
                $w=array_shift($keywords);
                $query->where('AG_NOMBRE LIKE "%' . $w .'%" OR AG_CODIGO LIKE "%' . $w .'%"');
                foreach ($keywords as $w) {
                    if(!empty($w)) {
                        $query->andWhere('AG_NOMBRE LIKE "%' . $w .'%" OR AG_CODIGO LIKE "%' . $w .'%"');
                    }
                }
            }

            $query->orderBy('AG_NOMBRE');
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out = [];
            $out['results'] = array_values($data);
            // foreach ($data as $d) {
                
            //     $out[] = ['text' => ' [' . $d['AG_CODIGO'].'] '.$d['AG_NOMBRE'] , 'id' => $d['AG_CODIGO']];
            // }
            // echo Json::encode($out);
            return Json::encode($out);
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}

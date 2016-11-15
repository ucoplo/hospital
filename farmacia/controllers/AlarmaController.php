<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Alarma;
use farmacia\models\AlarmaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\helpers\Json;
use farmacia\models\ArticGral;
/**
 * AlarmaController implements the CRUD actions for Alarma model.
 */
class AlarmaController extends Controller
{
      public $CodController="021";
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
     * Lists all Alarma models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AlarmaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Alarma model.
     * @param string $AL_CODMON
     * @param string $AL_DEPOSITO
     * @return mixed
     */
    public function actionView($AL_CODMON, $AL_DEPOSITO)
    {
        return $this->render('view', [
            'model' => $this->findModel($AL_CODMON, $AL_DEPOSITO),
        ]);
    }

    /**
     * Creates a new Alarma model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Alarma();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'AL_CODMON' => $model->AL_CODMON, 'AL_DEPOSITO' => $model->AL_DEPOSITO]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Alarma model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $AL_CODMON
     * @param string $AL_DEPOSITO
     * @return mixed
     */
    public function actionUpdate($AL_CODMON, $AL_DEPOSITO)
    {
        $model = $this->findModel($AL_CODMON, $AL_DEPOSITO);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'AL_CODMON' => $model->AL_CODMON, 'AL_DEPOSITO' => $model->AL_DEPOSITO]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Alarma model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $AL_CODMON
     * @param string $AL_DEPOSITO
     * @return mixed
     */
    public function actionDelete($AL_CODMON, $AL_DEPOSITO)
    {
        $this->findModel($AL_CODMON, $AL_DEPOSITO)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Alarma model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $AL_CODMON
     * @param string $AL_DEPOSITO
     * @return Alarma the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($AL_CODMON, $AL_DEPOSITO)
    {
        if (($model = Alarma::findOne(['AL_CODMON' => $AL_CODMON, 'AL_DEPOSITO' => $AL_DEPOSITO])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

     /*
    Esta acción es llamada desde el autocompletar. Busca por código y por descripción
    */

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

}

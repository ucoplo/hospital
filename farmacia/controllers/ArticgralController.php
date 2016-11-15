<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\ArticGral;
use farmacia\models\ArticGralSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ArticGralController implements the CRUD actions for ArticGral model.
 */
class ArticgralController extends Controller
{
      public $CodController="014";
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
     * Lists all ArticGral models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticGralSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ArticGral model.
     * @param string $id
     * @return mixed
     */
    public function actionView($AG_CODIGO,$AG_DEPOSITO)
    {
        return $this->render('view', [
            'model' => $this->findModel($AG_CODIGO,$AG_DEPOSITO),
        ]);
    }

    /**
     * Creates a new ArticGral model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ArticGral();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'AG_CODIGO' => $model->AG_CODIGO,'AG_DEPOSITO' => $model->AG_DEPOSITO]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ArticGral model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($AG_CODIGO,$AG_DEPOSITO)
    {
        $model = $this->findModel($AG_CODIGO,$AG_DEPOSITO);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'AG_CODIGO' => $model->AG_CODIGO,'AG_DEPOSITO' => $model->AG_DEPOSITO]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ArticGral model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($AG_CODIGO,$AG_DEPOSITO)
    {
        $this->findModel($AG_CODIGO,$AG_DEPOSITO)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ArticGral model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ArticGral the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($AG_CODIGO,$AG_DEPOSITO)
    {
        if (($model = ArticGral::findOne($AG_CODIGO,$AG_DEPOSITO)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

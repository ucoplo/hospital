<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Calle;
use farmacia\models\CalleBuscar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Json;

/**
 * CalleController implements the CRUD actions for Calle model.
 */
class CalleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Calle models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CalleBuscar();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Calle model.
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
     * Creates a new Calle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Calle();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->CA_CODIGO]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Calle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->CA_CODIGO]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Calle model.
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
     * Finds the Calle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Calle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Calle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
    Esta acción es llamada desde el autocompletar. Busca por código y por descripción
    */

    public function actionQuery($q = null) {
        $out = [];

        $query = new Query;
        
        $query->select(['CA_CODIGO','CA_NOM'])
            ->from(Calle::tableName())
            ->where('CA_NOM LIKE "%' . $q .'%" OR CA_CODIGO LIKE "%' . $q .'%"')
            ->orderBy('CA_NOM');
        $command = $query->createCommand();
        $data = $command->queryAll();
        foreach ($data as $d) {
            $out[] = ['value' => '[' . $d['CA_CODIGO'] . '] ' . $d['CA_NOM'], 'cod' => $d['CA_CODIGO']];
        }
        echo Json::encode($out);
    }
}

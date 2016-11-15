<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Barrio;
use farmacia\models\BarrioBuscar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Json;

/**
 * BarrioController implements the CRUD actions for Barrio model.
 */
class BarrioController extends Controller
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
     * Lists all Barrio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BarrioBuscar();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Barrio model.
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
     * Creates a new Barrio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Barrio();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->BA_CODIGO]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Barrio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->BA_CODIGO]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Barrio model.
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
     * Finds the Barrio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Barrio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Barrio::findOne($id)) !== null) {
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
        
        $query->select(['BA_CODIGO','BA_NOMBRE'])
            ->from(Barrio::tableName())
            ->where('BA_NOMBRE LIKE "%' . $q .'%" OR BA_CODIGO LIKE "%' . $q .'%"')
            ->orderBy('BA_NOMBRE');
        $command = $query->createCommand();
        $data = $command->queryAll();
        foreach ($data as $d) {
            $out[] = ['value' => $d['BA_NOMBRE'], 'cod' => $d['BA_CODIGO']];
        }
        echo Json::encode($out);
    }
}

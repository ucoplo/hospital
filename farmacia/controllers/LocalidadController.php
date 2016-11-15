<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Localidad;
use farmacia\models\LocalidadBuscar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Json;

/**
 * LocalidadController implements the CRUD actions for Localidad model.
 */
class LocalidadController extends Controller
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
     * Lists all Localidad models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocalidadBuscar();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Localidad model.
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
     * Creates a new Localidad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Localidad();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->LO_COD]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Localidad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->LO_COD]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Localidad model.
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
     * Finds the Localidad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Localidad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Localidad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /*
    Esta acción es llamada desde el autocompletar. Busca por código y por descripción
    */

    public function actionQuery($q = null) {
        $query = new Query;
        
        $query->select(['LO_DETALLE','LO_COD'])
            ->from(Localidad::tableName())
            ->where('LO_DETALLE LIKE "%' . $q .'%" OR LO_COD LIKE "%' . $q .'%"')
            ->orderBy('LO_DETALLE');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = ['value' => '[' . $d['LO_COD'] . '] ' . $d['LO_DETALLE'], 'cod' => $d['LO_COD']];
        }
        echo Json::encode($out);
    }
}

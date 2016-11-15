<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\ObraSocial;
use farmacia\models\ObraSocialBuscar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Json;

/**
 * ObraSocialController implements the CRUD actions for ObraSocial model.
 */
class ObraSocialController extends Controller
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
     * Lists all ObraSocial models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ObraSocialBuscar();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ObraSocial model.
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
     * Creates a new ObraSocial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ObraSocial();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->OB_COD]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ObraSocial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->OB_COD]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ObraSocial model.
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
     * Finds the ObraSocial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ObraSocial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ObraSocial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



    /*
    Esta acción es llamada desde el autocompletar. Busca por código y por descripción.
    */

    public function actionQuery($q = null, $art = null) {
        $query = new Query;
        $esART = $art? 'OB_ARTSEG = "T" AND ' : '';
        
        $query->select(['OB_NOM','OB_COD'])
            ->from(ObraSocial::tableName())
            ->where($esART . '(OB_NOM LIKE "%' . $q .'%" OR OB_COD LIKE "%' . $q . '%" OR OB_SINON LIKE "%' . $q . '%")')
            ->orderBy('OB_NOM');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = ['value' => '[' . $d['OB_COD'] . '] ' . $d['OB_NOM'], 'cod' => $d['OB_COD']];
        }
        echo Json::encode($out);
    }
}

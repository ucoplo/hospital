<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\TipoVivienda;
use farmacia\models\TipoViviendaBuscar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Json;

/**
 * TipoViviendaController implements the CRUD actions for TipoVivienda model.
 */
class TipoViviendaController extends Controller
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
     * Lists all TipoVivienda models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TipoViviendaBuscar();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TipoVivienda model.
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
     * Creates a new TipoVivienda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TipoVivienda();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->TV_CODIGO]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TipoVivienda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->TV_CODIGO]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TipoVivienda model.
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
     * Finds the TipoVivienda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TipoVivienda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TipoVivienda::findOne($id)) !== null) {
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
        
        $query->select(['TV_CODIGO','TV_DETALLE'])
            ->from(TipoVivienda::tableName())
            ->where('TV_DETALLE LIKE "%' . $q .'%" OR TV_CODIGO LIKE "%' . $q .'%"')
            ->orderBy('TV_DETALLE');
        $command = $query->createCommand();
        $data = $command->queryAll();
        foreach ($data as $d) {
            $out[] = ['value' => '[' . $d['TV_CODIGO'] . '] ' . $d['TV_DETALLE'], 'cod' => $d['TV_CODIGO']];
        }
        echo Json::encode($out);
    }
}

<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\SituacionLaboral;
use farmacia\models\SituacionLaboralBuscar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Json;

/**
 * SituacionLaboralController implements the CRUD actions for SituacionLaboral model.
 */
class SituacionLaboralController extends Controller
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
     * Lists all SituacionLaboral models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SituacionLaboralBuscar();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SituacionLaboral model.
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
     * Creates a new SituacionLaboral model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SituacionLaboral();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cod]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SituacionLaboral model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cod]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SituacionLaboral model.
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
     * Finds the SituacionLaboral model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SituacionLaboral the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SituacionLaboral::findOne($id)) !== null) {
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
        
        $query->select(['cod','descri'])
            ->from(SituacionLaboral::tableName())
            ->where('descri LIKE "%' . $q .'%" OR cod LIKE "%' . $q .'%"')
            ->orderBy('descri');
        $command = $query->createCommand();
        $data = $command->queryAll();
        foreach ($data as $d) {
            $out[] = ['value' => '[' . $d['cod'] . '] ' . $d['descri'], 'cod' => $d['cod']];
        }
        echo Json::encode($out);
    }
}

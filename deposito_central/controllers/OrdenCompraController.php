<?php

namespace deposito_central\controllers;

use Yii;
use deposito_central\models\OrdenCompra;
use deposito_central\models\OrdenCompraSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdenCompraController implements the CRUD actions for OrdenCompra model.
 */
class OrdencompraController extends Controller
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
     * Lists all OrdenCompra models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdenCompraSearch();
        $orden = new OrdenCompra();
        $params = Yii::$app->request->queryParams; 
        //print_r($params);
        if (isset($params[$searchModel->formName()]))
        {
            $numero = $params[$searchModel->formName()]['numero_oc'];
            $ejercicio = $params[$searchModel->formName()]['ejercicio_oc'];
            $params[$searchModel->formName()]['OC_NRO'] = $ejercicio.$numero;
            //print_r($params[$searchModel->formName()]['OC_NRO']);    
            $orden = OrdenCompra::findOne($ejercicio.$numero);
        }

        
        $dataProvider = $searchModel->search($params);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'orden'=>$orden,
        ]);
    }

    /**
     * Displays a single OrdenCompra model.
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
     * Creates a new OrdenCompra model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrdenCompra();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->OC_NRO]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OrdenCompra model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->OC_NRO]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OrdenCompra model.
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
     * Finds the OrdenCompra model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return OrdenCompra the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrdenCompra::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

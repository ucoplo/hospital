<?php

namespace farmacia\controllers;

use Yii;
use farmacia\models\Paciente;
use farmacia\models\PacienteBuscar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Json;

define('ORIGEN_FARMACIA', 'FAR');

/**
 * PacienteController implements the CRUD actions for Paciente model.
 */
class PacienteController extends Controller
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
     * Lists all Paciente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PacienteBuscar();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Paciente model.
     * @param string $PA_TIPDOC
     * @param string $PA_NUMDOC
     * @param string $PA_HISCLI
     * @return mixed
     */
    public function actionView($PA_TIPDOC, $PA_NUMDOC, $PA_HISCLI)
    {
        return $this->render('view', [
            'model' => $this->findModel($PA_TIPDOC, $PA_NUMDOC, $PA_HISCLI),
        ]);
    }

    /**
     * Creates a new Paciente model.
     * 
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Paciente();

        if ($model->load(Yii::$app->request->post()) && $model->save())  {
          return $this->redirect(['ambulatorios_ventanilla/create', 'hiscli' => $model->PA_HISCLI]);
        } else {
            $model->PA_ORIGEN = ORIGEN_FARMACIA;
            $model->PA_TIPDOC = 'DNI';

            $model->PA_NACION = '01';
            $model->PA_CODPAIS = '200';
            $model->PA_CODPRO = '06';
            $model->PA_CODLOC = '056';
            $model->PA_CODPAR = '056';
            $model->PA_TELEF = '291';

            return $this->render('create', ['model' => $model,'termino'=>false]);
        }
    }

    /**
     * Updates an existing Paciente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $PA_TIPDOC
     * @param string $PA_NUMDOC
     * @param string $PA_HISCLI
     * @return mixed
     */
    public function actionUpdate($PA_TIPDOC, $PA_NUMDOC, $PA_HISCLI)
    {
        $model = $this->findModel($PA_TIPDOC, $PA_NUMDOC, $PA_HISCLI);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['ambulatorios_ventanilla/create', 'hiscli' => $model->PA_HISCLI]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Paciente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $PA_TIPDOC
     * @param string $PA_NUMDOC
     * @param string $PA_HISCLI
     * @return mixed
     */
    public function actionDelete($PA_TIPDOC, $PA_NUMDOC, $PA_HISCLI)
    {
        $this->findModel($PA_TIPDOC, $PA_NUMDOC, $PA_HISCLI)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Paciente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $PA_TIPDOC
     * @param string $PA_NUMDOC
     * @param string $PA_HISCLI
     * @return Paciente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($PA_TIPDOC, $PA_NUMDOC, $PA_HISCLI)
    {
        if (($model = Paciente::findOne(['PA_TIPDOC' => $PA_TIPDOC, 'PA_NUMDOC' => $PA_NUMDOC, 'PA_HISCLI' => $PA_HISCLI])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function proximaHc() {
        $query = new Query;
        
        $query->from(Paciente::tableName());
        $rows = $query->max('PA_HISCLI');
        return sprintf('%06d', $rows + 1);
    }

    public function actionBuscarSugerencias()
    {
        $searchModel = new PacienteBuscar();
        $dataProvider = $searchModel->buscarSugerencias(Yii::$app->request->queryParams);

        return $this->renderPartial('buscarSugerencias', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     /*
    Esta acción es llamada desde el autocompletar. Busca por código y por descripción
    */

    public function actionQuery($q = null) {

        try {
            $query = new Query;
            $query->select(['PA_APENOM', 'PA_NUMDOC', 'PA_FECNAC', 'PA_HISCLI'])->from(Paciente::tableName());
            $query->where('PA_APENOM LIKE "%' . $q .'%" OR PA_NUMDOC LIKE "%' . $q .'%" OR PA_HISCLI LIKE "%' . $q .'%"');
            $keywords=explode(' ', $q);
            if(count($keywords)>0) { // caso búsqueda con multiples keywords
                $w=array_shift($keywords);
                $query->where('PA_APENOM LIKE "%' . $w .'%" OR PA_NUMDOC LIKE "%' . $w .'%" OR PA_HISCLI LIKE "%' . $w .'%"');
                foreach ($keywords as $w) {
                    if(!empty($w)) {
                        $query->andWhere('PA_APENOM LIKE "%' . $w .'%" OR PA_NUMDOC LIKE "%' . $w .'%" OR PA_HISCLI LIKE "%' . $w .'%"');
                    }
                }
            }

            $query->orderBy('PA_APENOM');
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out = [];
            foreach ($data as $d) {
                 $fecha_nac = Yii::$app->formatter->asDate($d['PA_FECNAC'],'php:d-m-Y');
                $out[] = ['value' => $d['PA_APENOM'] . ' [HC=' . $d['PA_HISCLI'].'] [DNI='.$d['PA_NUMDOC'].'] [FECHA NAC='.$fecha_nac.']', 'apenom' => $d['PA_APENOM'], 'cod' => $d['PA_HISCLI']];
            }
            echo Json::encode($out);
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionFetch_paciente() {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $p=Paciente::findOne(["PA_HISCLI" => $post["PA_HISCLI"]]);
           
            $paciente['OSDescripcion']=$p->obraSocialDescripcion;
            $paciente['localidadNacimientoDescripcion']=$p->localidadNacimientoDescripcion;
            $paciente['NA_DETALLE']=$p->nacionalidadDescripcion;
            $paciente['PR_DETALLE']=$p->provinciaDescripcion;
            $paciente['PT_DETALLE']=$p->partidoDescripcion;
            $paciente['LO_DETALLE']=$p->localidadDescripcion;
            $paciente['sexo']=$p->sexo;
            $paciente['paciente']=$p;

            return \yii\helpers\Json::encode($paciente);
        }
    }
}

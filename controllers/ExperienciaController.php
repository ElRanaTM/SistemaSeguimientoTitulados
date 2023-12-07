<?php

namespace app\controllers;

use app\models\Area;
use app\models\Conocimientos;
use app\models\Experiencia;
use app\models\ExperienciaSearch;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * ExperienciaController implements the CRUD actions for Experiencia model.
 */
class ExperienciaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function checkIfAdmin(){
        if (!Yii::$app->user->isGuest){
            if (Yii::$app->user->identity->user_type === 'admin' || Yii::$app->user->identity->user_type === 'director' || Yii::$app->user->identity->user_type === 'SuperAdmin'){
                return true;
            }
            else{
                return false;
            }
        }
    }
    public function checkIfUserIsTitulado(){
        if (Yii::$app->user->identity->user_type === 'titulado') {
            $user = Yii::$app->user->identity;
            if ($user && $user->titulado) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Lists all Experiencia models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        if (!$this->checkIfAdmin()) {
            if (!$this->checkIfUserIsTitulado()) {
                Yii::$app->session->setFlash('error', 'No puede acceder a esta página.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        $searchModel = new ExperienciaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $user = Yii::$app->user->identity;
        if ($user && $user->user_type === 'titulado' && $user->titulado !== null) {

            $ci = $user->titulado->CI;

            $dataProvider->query->andWhere(['CI' => $ci]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Experiencia model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(["site/index"]);
        }

        $model = $this->findModel($id);

        if (Yii::$app->user->identity->user_type === 'titulado') {
            if ($model->CI === Yii::$app->user->identity->titulado->CI) {
                return $this->render('view', ['model' => $model]);
            } else {
                Yii::$app->session->setFlash('error', 'No puede acceder a este sitio.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new Experiencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        $model = new Experiencia();

        if ($this->request->isPost) {
            $model->CI = Yii::$app->user->identity->titulado->CI;
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Experiencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }

        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Experiencia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $experiencia = $this->findModel($id);

        if (!$experiencia) {
            throw new NotFoundHttpException('La experiencia laboral no se encontró.');
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $areas = $experiencia->areas;

            foreach ($areas as $area) {
                Conocimientos::deleteAll(['idAreaDesempenio' => $area->id]);
                $area->delete();
            }
            $experiencia->delete();
            $transaction->commit();

            Yii::$app->session->setFlash('success', 'La experiencia laboral se eliminó correctamente.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'No se pudo eliminar la experiencia laboral debido a un error.');
        }
        return $this->redirect(['index']);
    }



    /**
     * Finds the Experiencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Experiencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Experiencia::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGuardarComentario()
    {
        $comentarios = new Conocimientos();
        $areaId = Yii::$app->request->post('areaId');
        $descripcion = Yii::$app->request->post('Conocimientos')['Descripcion'];
        $comentarios->idAreaDesempenio = $areaId;
        $comentarios->Descripcion = $descripcion;
            if($comentarios->save(false)){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => true, 'message' => 'Comentario guardado exitosamente.'];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['success' => false, 'message' => 'Ocurrió un error al guardar el comentario. coment = '. Yii::$app->request->post('Conocimientos')['Descripcion']];
    }

    public function actionEliminarComentario()
    {
        $areaId = Yii::$app->request->post('areaId');
        $commentIndex = Yii::$app->request->post('commentIndex');
        
        //die(var_dump(Yii::$app->request->post()));
        $area = Area::findOne($areaId);
        if ($area) {
            $comentarios = $area->conocimientos;
            if (isset($comentarios[$commentIndex])) {
                $comentario = $comentarios[$commentIndex];
                $comentario->delete();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => true];
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['success' => false];
    }

}

<?php

namespace app\controllers;

use app\models\Estudios;
use app\models\EstudiosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * EstudiosController implements the CRUD actions for Estudios model.
 */
class EstudiosController extends Controller
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
     * Lists all Estudios models.
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

        $searchModel = new EstudiosSearch();
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
     * Displays a single Estudios model.
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
     * Creates a new Estudios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        $model = new Estudios();
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save(false)) {
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
     * Updates an existing Estudios model.
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
     * Deletes an existing Estudios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Estudios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Estudios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Estudios::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

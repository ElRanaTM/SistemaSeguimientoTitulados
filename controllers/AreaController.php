<?php

namespace app\controllers;

use app\models\Area;
use app\models\AreaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Conocimientos;
use Yii;

/**
 * AreaController implements the CRUD actions for Area model.
 */
class AreaController extends Controller
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

    /**
     * Lists all Area models.
     *
     * @return string
     */
    public function actionIndex($expID)
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }

        $searchModel = new AreaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $user = Yii::$app->user->identity;
        if ($user && $user->user_type === 'user' && $user->titulado !== null) {
            //$ci = $user->titulado->experiencias->AreaDesempenio;
            $dataProvider->query->andWhere(['idExperienciaLaboral' => $expID]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Area model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Area model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($idExperienciaLaboral)
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        $user = Yii::$app->user->identity;
        if (!$user->titulado) {
            throw new NotFoundHttpException('La experiencia no se encontró.');
        }

        $model = new Area();
        $model->idExperienciaLaboral = $idExperienciaLaboral;
        $model->idCarrera = $model->idCarrera;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if (array_key_exists('Conocimientos', Yii::$app->request->post('Area'))) {
                    $conocimientosData = Yii::$app->request->post('Area')['Conocimientos'];
                    foreach ($conocimientosData['Descripcion'] as $descripcion) {
                        $conocimientoModel = new Conocimientos();
                        $conocimientoModel->Descripcion = $descripcion;
                        $conocimientoModel->idAreaDesempenio = $model->id;
                        $conocimientoModel->FechaActualizacion = date('Y-m-d');
                        $conocimientoModel->save();
                    }
                }

                return $this->redirect(['experiencia/index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Area model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $idExperienciaLaboral)
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        $model = $this->findModel($id);

        $model->idExperienciaLaboral = $idExperienciaLaboral;
        $model->idCarrera = $model->idCarrera;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if (array_key_exists('Conocimientos', Yii::$app->request->post('Area'))) {
                    $conocimientosData = Yii::$app->request->post('Area')['Conocimientos'];
                    foreach ($conocimientosData['Descripcion'] as $descripcion) {
                        $conocimientoModel = new Conocimientos();
                        $conocimientoModel->Descripcion = $descripcion;
                        $conocimientoModel->idAreaDesempenio = $model->id;
                        $conocimientoModel->FechaActualizacion = date('Y-m-d');
                        $conocimientoModel->save();
                    }
                }

                return $this->redirect(['experiencia/index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Area model.
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
     * Finds the Area model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Area the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Area::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

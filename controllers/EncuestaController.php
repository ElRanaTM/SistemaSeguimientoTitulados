<?php

namespace app\controllers;

use app\models\Encuesta;
use app\models\EncuestaSearch;
use app\models\Opciones;
use app\models\Pregunta;
use app\models\Resptitulado;
use app\models\Respuesta;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EncuestaController implements the CRUD actions for Encuesta model.
 */
class EncuestaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['*'],
                    'rules' => [
                        [
                            //'actions' => ['*'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                    'denyCallback' => function($rule, $action) {
                        return Yii::$app->response->redirect(['site/index']); 
                    },
                ],
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
     * Lists all Encuesta models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EncuestaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        if(!$this->checkIfAdmin()){
            if ($this->checkIfUserIsTitulado()) {
                $carreras = Yii::$app->user->identity->titulado->carreras;

                $codigoCarreras = [];
                $codigoSedes = [];

                foreach ($carreras as $carrera) {
                    $codigoCarreras[] = $carrera->CodigoCarrera;
                    $codigoSedes[] = $carrera->CodigoSede;
                }
                $dataProvider->query->andWhere(['CodigoCarrera' => $codigoCarreras, 'CodigoSede' => $codigoSedes]);
            } else {
                Yii::$app->session->setFlash('error', 'No puede acceder a esta página.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        $codigoCarrera = Yii::$app->session->get('codigoCarrera');
        $codigoSede = Yii::$app->session->get('codigoSede');

        if ($codigoCarrera !== null || $codigoSede !== null) {
            $dataProvider->query->andWhere(['codigoCarrera' => $codigoCarrera, 'CodigoSede' => $codigoSede]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Encuesta model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(!$this->checkIfAdmin()){
            if ($this->checkIfUserIsTitulado()) {
                Yii::$app->session->setFlash('error', 'No puede acceder a esta página.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        $model = $this->findModel($id);

        $model->load('preguntas.respuestas');

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Encuesta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    /*      //imprimir errores json pretty
            function dump_JSON ($a, $echo = true) {
                $str =  '<pre>' . htmlentities(json_encode($a, JSON_PRETTY_PRINT)) . '</pre>';
                if ($echo) echo $str;
                return $str;
            }
            die(dump_JSON(Yii::$app->request->post()));
    */
    public function actionCreate()
    {
        if(!$this->checkIfAdmin()){
            if ($this->checkIfUserIsTitulado()) {
                Yii::$app->session->setFlash('error', 'No puede acceder a esta página.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        $model = new Encuesta();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($model->save()) {
                    $preguntaData = Yii::$app->request->post('Pregunta');

                    foreach ($preguntaData as $preguntaId => $preguntaAttributes) {
                        $pregunta = new Pregunta();
                        $pregunta->attributes = $preguntaAttributes;
                        $pregunta->idEncuesta = $model->id;

                        if (!$pregunta->validate() || !$pregunta->save()) {
                            Yii::$app->session->setFlash('error', 'Error al guardar las preguntas.');
                            $transaction->rollBack();
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                        $opcionData = Yii::$app->request->post('Pregunta')[$preguntaId]['Opciones'] ?? [];
                        foreach ($opcionData as $opcionText) {
                            $opcion = new Opciones();
                            $opcion->Opcion = $opcionText;
                            $opcion->idPregunta = $pregunta->id;

                            if (!$opcion->save()) {
                                Yii::$app->session->setFlash('error', 'Error al guardar las opciones.');
                                $transaction->rollBack();
                                return $this->render('create', [
                                    'model' => $model,
                                ]);
                            }
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Encuesta creada exitosamente.');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Error al guardar la encuesta.');
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                //Yii::error($e->getMessage());
                Yii::$app->session->setFlash('error', 'Ocurrió un error inesperado.');
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }



    /**
     * Updates an existing Encuesta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!$this->checkIfAdmin()){
            if ($this->checkIfUserIsTitulado()) {
                Yii::$app->session->setFlash('error', 'No puede acceder a esta página.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        $encuesta = Encuesta::findOne($id);

        if (!$encuesta) {
            throw new \yii\web\NotFoundHttpException('La encuesta no se encontró.');
        }
        $preguntasExistente = Pregunta::findAll(['idEncuesta' => $encuesta->id]);
        if ($encuesta->load(Yii::$app->request->post()) && $encuesta->validate()) {
            
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($encuesta->save()) {
                    foreach ($preguntasExistente as $preguntaExistente) {
                        $preguntaId = $preguntaExistente->id;
                        Opciones::deleteAll(['idPregunta' => $preguntaId]);
                        Respuesta::deleteAll(['idPregunta' => $preguntaId]);
                    }
                    Pregunta::deleteAll(['idEncuesta' => $encuesta->id]);
                    Resptitulado::deleteAll(['idEncuesta' => $encuesta->id]);
                    $preguntaData = Yii::$app->request->post('Pregunta');

                    foreach ($preguntaData as $preguntaId => $preguntaAttributes) {
                        $pregunta = new Pregunta();
                        $pregunta->attributes = $preguntaAttributes;
                        $pregunta->idEncuesta = $encuesta->id;

                        if (!$pregunta->validate() || !$pregunta->save()) {
                            Yii::$app->session->setFlash('error', 'Error al guardar las preguntas.');
                            $transaction->rollBack();
                            return $this->render('update', [
                                'model' => $encuesta,
                                'preguntasExistente' => $preguntasExistente,
                            ]);
                        }
                        $opcionData = Yii::$app->request->post('Pregunta')[$preguntaId]['Opciones'] ?? [];
                        foreach ($opcionData as $opcionText) {
                            $opcion = new Opciones();
                            $opcion->Opcion = $opcionText;
                            $opcion->idPregunta = $pregunta->id;

                            if (!$opcion->save()) {
                                Yii::$app->session->setFlash('error', 'Error al guardar las opciones.');
                                $transaction->rollBack();
                                return $this->render('update', [
                                    'model' => $encuesta,
                                    'preguntasExistente' => $preguntasExistente,
                                ]);
                            }
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Encuesta actualizada exitosamente.');
                    return $this->redirect(['view', 'id' => $encuesta->id]);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error al guardar la encuesta.');
                }
            } catch (\Exception $e) {
                Yii::error($e->getMessage());
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Ocurrió un error inesperado.');
            }
        }

        return $this->render('update', [
            'model' => $encuesta,
            'preguntasExistente' => $preguntasExistente,
        ]);
    }

    /**
     * Deletes an existing Encuesta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(!$this->checkIfAdmin()){
            if ($this->checkIfUserIsTitulado()) {
                Yii::$app->session->setFlash('error', 'No puede acceder a esta página.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        $encuesta = $this->findModel($id);

        if (!$encuesta) {
            throw new \yii\web\NotFoundHttpException('La encuesta no se encontró.');
        }
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $preguntas = Pregunta::findAll(['idEncuesta' => $encuesta->id]);
            foreach ($preguntas as $pregunta) {
                Opciones::deleteAll(['idPregunta' => $pregunta->id]);
                Respuesta::deleteAll(['idPregunta' => $pregunta->id]);
            }
            Resptitulado::deleteAll(['idEncuesta' => $encuesta->id]);
            Pregunta::deleteAll(['idEncuesta' => $encuesta->id]);
            $encuesta->delete();
            $transaction->commit();
            
            Yii::$app->session->setFlash('success', 'La encuesta se eliminó correctamente.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
            Yii::$app->session->setFlash('error', 'Ocurrió un error al eliminar la encuesta.');

            return $this->redirect(['index']);
        }

        return $this->redirect(['index']);
    }


    /**
     * Finds the Encuesta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Encuesta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Encuesta::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionResponderEncuesta($id)
    {
        $encuesta = $this->findModel($id);
        if (!$encuesta->Estado){
            Yii::$app->session->setFlash('error', 'El periodo para enviar respuesta ha finalizado');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }

        $respuesta = new Resptitulado();

        if ($respuesta->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();

            try {
                $respuesta->idEncuesta = $encuesta->id;
                $respuesta->CI = Yii::$app->user->identity->titulado->CI;
                $respuesta->FechaRespuesta = date('Y-m-d H:i:s');
                if ($respuesta->save()) {
                    $respuestaData = Yii::$app->request->post('Resptitulado')['respuestas'];
                    foreach ($respuestaData as $respuestaDataId => $respuestaDatos) {
                        $resp = new Respuesta();
                        $resp->TextoRespuesta = is_array($respuestaDatos) ? implode(", ", $respuestaDatos) : $respuestaDatos ;
                        $resp->idEncuesta = $encuesta->id;
                        $resp->user_id = Yii::$app->user->id;
                        $resp->idPregunta = $respuestaDataId;
                        $resp->idRespTitulado = $respuesta->id;
                        $resp->FechaRespuesta = date('Y-m-d H:i:s');

                        if (!$resp->validate() || !$resp->save()) {
                            Yii::$app->session->setFlash('error', 'Error al guardar las respuestas.');
                            $transaction->rollBack();
                            return $this->render('responder-encuesta', [
                                'encuesta' => $encuesta,
                                'respuesta' => $respuesta,
                            ]);
                        }
                    }
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Encuesta respondida.');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Error al guardar la respuesta.');
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                //Yii::error($e->getMessage());
                Yii::$app->session->setFlash('error', 'Ocurrió un error inesperado.' . $e->getMessage());
                $transaction->rollBack();
            }
        }
        return $this->render('responder-encuesta', [
            'encuesta' => $encuesta,
            'respuesta' => $respuesta,
        ]);
    }

    public function actionVerRespuesta($id, $ci)
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        if (Yii::$app->user->identity->user_type === 'titulado') {
            $actualCI = Yii::$app->user->identity->titulado->CI;
            if ($ci != $actualCI) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        $model = $this->findModel($id);

        $respuestas = Respuesta::find()
            ->joinWith(['idRespTitulado0' => function ($query) use ($ci) {
                $query->andWhere(['resptitulado.CI' => $ci]);
            }])
            ->where(['respuesta.idEncuesta' => $id])
            ->all();

        return $this->render('ver-respuesta', [
            'model' => $model,
            'respuestas' => $respuestas,
        ]);
    }

}

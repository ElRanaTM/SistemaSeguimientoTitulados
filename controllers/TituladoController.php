<?php

namespace app\controllers;

use app\models\Area;
use app\models\Carrera;
use app\models\Conocimientos;
use app\models\Estudios;
use app\models\Experiencia;
use app\models\Resptitulado;
use app\models\Respuesta;
use app\models\Titulado;
use app\models\TituladoSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box; 

/**
 * TituladoController implements the CRUD actions for Titulado model.
 */
class TituladoController extends Controller
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
     * Lists all Titulado models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        if(!$this->checkIfAdmin()){
            if ($this->checkIfUserIsTitulado()) {
                return $this->redirect(['view', 'CI' => Yii::$app->user->identity->titulado->CI]);
            } else {
                Yii::$app->session->setFlash('error', 'No puede acceder a esta página.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        $searchModel = new TituladoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        if (Yii::$app->user->identity->carreras != null) {
            $carrerasIds = Titulado::find()
                ->joinWith('carreras')
                ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])
                ->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                ->select('titulado.CI')
                ->column();
            $dataProvider->query->andWhere(['CI' => $carrerasIds]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Titulado model.
     * @param string $CI Ci
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($CI)
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        if (Yii::$app->user->identity->user_type === 'titulado') {
            $actualCI = Yii::$app->user->identity->titulado->CI;
            if ($CI != $actualCI) {
                return $this->render('view', [
                    'model' => $this->findModel($actualCI),
                ]);
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($CI),
        ]);
    }

    /**
     * Creates a new Titulado model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        $model = new Titulado();

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            //$temp = file_get_contents()
            if ($model->validate()) {
                $tituladoId = $model->CI;
                $imgNombre = 'titulado_' . $tituladoId . '.' . $model->imageFile->getExtension();
                $uploadPath = Yii::getAlias('@app') . '/web/images/titulados/' . $imgNombre;
                
                if ($model->imageFile->saveAs($uploadPath)) {
                    $fileSize = filesize($uploadPath);
                    $maxFileSize = 1024 * 1024 * 2;
                    if ($fileSize <= $maxFileSize) {
                        $model->Foto = $imgNombre;
                        $model->imageFile = 'data:image/' . $model->imageFile->getExtension() . ';base64,' . base64_encode(file_get_contents($uploadPath));
                        /*if (!empty($oldImage) && file_exists(Yii::getAlias('@app') . '/web/images/titulados/' . $oldImage)) {
                            unlink(Yii::getAlias('@app') . '/web/images/titulados/' . $oldImage);
                        }*/
                    } else {
                        Yii::$app->session->setFlash('error', 'La imagen debe tener un tamaño máximo de 2 MB.');
                    }
                }
                if ($model->save(false)) {
                    return $this->redirect(['view', 'CI' => $model->CI]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Titulado model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $CI Ci
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($CI)
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        $model = $this->findModel($CI);
        $oldImage = $model->Foto;

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate()) {
                if ($model->imageFile) {
                    $tituladoId = $model->CI;
                    $imgNombre = 'titulado_' . $tituladoId . '.' . $model->imageFile->getExtension();
                    $uploadPath = Yii::getAlias('@app') . '/web/images/titulados/' . $imgNombre;
                    if ($model->imageFile->saveAs($uploadPath)) {
                        $fileSize = filesize($uploadPath);
                        $maxFileSize = 1024 * 1024 * 2;
                        if ($fileSize <= $maxFileSize) {
                            $model->Foto = $imgNombre;
                            $model->imageFile = 'data:image/' . $model->imageFile->getExtension() . ';base64,' . base64_encode(file_get_contents($uploadPath));
                            /*if (!empty($oldImage) && file_exists(Yii::getAlias('@app') . '/web/images/titulados/' . $oldImage)) {
                                unlink(Yii::getAlias('@app') . '/web/images/titulados/' . $oldImage);
                            }*/
                        } else {
                            Yii::$app->session->setFlash('error', 'La imagen debe tener un tamaño máximo de 2 MB.');
                        }
                    }
                }
                if ($model->save(false)) {
                    return $this->redirect(['view', 'CI' => $model->CI]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }


    /**
     * Deletes an existing Titulado model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $CI Ci
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($CI)
    {
        $titulado = Titulado::findOne(['CI' => $CI]);

        if (!$titulado) {
            throw new NotFoundHttpException('El titulado no fue encontrado.');
        }
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($titulado->experiencias as $experiencia) {
                Conocimientos::deleteAll(['idAreaDesempenio' => $experiencia->areas]);
                Area::deleteAll(['idExperienciaLaboral' => $experiencia->id]);
            }
            Respuesta::deleteAll(['idRespTitulado' => $titulado->resptitulado->id]);
            Resptitulado::deleteAll(['CI' => $CI]);
            Estudios::deleteAll(['CI' => $CI]);
            Experiencia::deleteAll(['CI' => $CI]);
            Carrera::deleteAll(['CI' => $CI]);
            $titulado->delete();

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'El titulado se ha eliminado con éxito.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Ha ocurrido un error al eliminar el titulado: ');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Titulado model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $CI Ci
     * @return Titulado the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($CI)
    {
        if (($model = Titulado::findOne(['CI' => $CI])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El titulado no existe.');
    }
    
}

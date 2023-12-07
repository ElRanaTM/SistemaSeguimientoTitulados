<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Titulado;
use yii\data\Pagination;

use app\models\User;
use yii\web\BadRequestHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'cambiar-password', 'establecer-password', 'cambiar-datos', 'establecer-password', 'usuario', 'dashboard'],
                'rules' => [
                    [
                        'actions' => ['logout', 'cambiar-password', 'establecer-password', 'cambiar-datos', 'establecer-password', 'usuario', 'dashboard'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['establecer-password'],
                        'allow' => function ($action, $user) {
                            return $user->identity->hasChangedPassword;
                        },
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'passwordChangedFilter' => [
                'class' => 'app\filters\PasswordChangedFilter',
            ],
        ];
    }
/*
    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            return parent::beforeAction($action);
        }
        if ($action->id !== 'cambiarPassword' && $action->id !== 'establecerPassword') {
            \Yii::$app->user->identity->hasChangedPassword = false;
        }

        return parent::beforeAction($action);
    }
*/
    public function checkIfAdmin(){
        if (!Yii::$app->user->isGuest){
            if (Yii::$app->user->identity->user_type === 'admin'){
                $this->layout = 'admin';
            }
        }
    }

    public function checkIfFirstLogin(){
        if (!Yii::$app->user->isGuest){
                $user = Yii::$app->user->identity;
                $pattern = '/^\d{8}[A-Z]{2}$/';
                if (preg_match($pattern, $user->name)) {
                    return $this->redirect(['site/cambiar-datos', 'id' => $user->id]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $user = new User();
            return $this->render('login', ['user' => $user,]);
        }
        $this->checkIfAdmin();
        //$this->checkIfFirstLogin();
        $query = Titulado::find();

        $pagination = new Pagination([
                'defaultPageSize' => 10,
                'totalCount' => $query->count(),
            ]);
        
        $titulados = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
        
        return $this->render('index', [
                'titulados' => $titulados,
                'pagination' => $pagination,
            ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(["site/index"]);
        }

        $request = Yii::$app->request->post();
        $user = new User();
        if($request)
        {
            if ($user->load($request) && $user->loginUser())
            {
                return $this->redirect(["site/index"]);
            }

            $session = Yii::$app->session;
            $session->setFlash('errorMessages', $user->getErrors());
        }


        $user->password = '';
        return $this->render('login', [
            'user' => $user,
        ]);
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        //return $this->redirect(["site/index"]);
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        //return $this->redirect(["site/index"]);
        return $this->render('about');
    }

    //nuevo code by david
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest){
            if (Yii::$app->user->identity->user_type === 'SuperAdmin'){
            return $this->render('register');
            }
        }
        
        return $this->redirect(["site/index"]);
    }

    public function actionManual()
    {
        $pdfUrl = Yii::getAlias('@web') . '/manual/manual.pdf';

        return $this->render('manual', [
            'pdfUrl' => $pdfUrl,
        ]);
    }

    public function actionSignUp()
    {
        if (Yii::$app->user->identity->user_type != 'SuperAdmin'){
            return $this->redirect(["site/index"]);
        }
        $request = Yii::$app->request->post();

        $user = new User();
        $user->attributes = $request;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
        $session = Yii::$app->session;

        if($user->validate() && $user->save())
        {
            $session->setFlash('successMessage', 'Registro Exitoso');
            return $this->redirect(['site/login']);
        }

        $session->setFlash('errorMessages', $user->getErrors());
        return $this->redirect(['site/register']);
    }

    public function actionDashboard()
    {
        return $this->render('index');
    }

    public function actionUsuario()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }
        $this->checkIfAdmin();
        $model = User::findOne(Yii::$app->user->identity->id);
        return $this->render('usuario', [
            'model' => $model,
        ]);
    }

    public function actionCambiarEmail()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }
        $this->checkIfAdmin();
        $model = User::findOne(Yii::$app->user->identity->id);
        //die(var_dump(Yii::$app->request->post('User')['password']));
        if ($model->load(Yii::$app->request->post())) {
            //die(var_dump(Yii::$app->request->post('User')));
            //if ($model->validatePassword(Yii::$app->getSecurity()->generatePasswordHash(Yii::$app->request->post('User')['viejaPassword'])))
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Correo Electrónico actualizado con éxito.');
                return $this->redirect(['usuario']);
            }
            else {
                Yii::$app->session->setFlash('error', 'Hubo un error.');
            }   
        }

        return $this->render('cambiar-email', [
            'model' => $model,
        ]);
    }

    public function actionCambiarPassword()
    {
        $model = User::findOne(Yii::$app->user->identity->id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //die(var_dump($model->password , ' ======== ' ,Yii::$app->getSecurity()->generatePasswordHash(Yii::$app->request->post('User')['viejaPassword'])));
            //$hash = Yii::$app->getSecurity()->generatePasswordHash($model->viejaPassword);
            if ($model->validarPassword()) {
                $model->setHasChangedPassword(true);
                return $this->redirect(['establecer-password']);
            } else {
                Yii::$app->session->setFlash('error', 'La contraseña actual es incorrecta.');
            }
        }
        $model->password = '';
        $model->setHasChangedPassword(true);
        return $this->render('cambiar-password', [
            'model' => $model,
        ]);
    }

    public function actionEstablecerPassword()
    {
        $model = User::findOne(Yii::$app->user->identity->id);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (Yii::$app->request->post('User')['nuevaPassword'] == Yii::$app->request->post('User')['confirmarPassword']){
                if ($model->cambiarPassword(Yii::$app->request->post('User')['nuevaPassword'])) {
                    Yii::$app->session->setFlash('success', 'La contraseña se ha cambiado correctamente.');
                    $model->setHasChangedPassword(false);
                    return $this->redirect(['usuario']);
                } else {
                    Yii::$app->session->setFlash('error', 'Se produjo un error al cambiar la contraseña.');
                }
            }else {
                Yii::$app->session->setFlash('error', 'Debe validar la contraseña.');
            }
            
        }

        return $this->render('establecer-password', [
            'model' => $model,
        ]);
    }


    public function actionCambiarDatos($id)
    {
        return $this->redirect(['site/index']);
        /*
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
            if (!Yii::$app->user->identity->id == $id){
                return $this->redirect(['site/index']);
            }
        } 
	    $usuario = User::findOne($id);
        $oldName = $usuario->name;

        if ($usuario->load(Yii::$app->request->post()) && $usuario->validate()) {
            $newName = Yii::$app->request->post('User')['name'];
            $newPassword = Yii::$app->request->post('User')['password'];
            //die(var_dump($oldName, Yii::$app->request->post('User')['name']));

            if ($usuario->changeUserData($oldName, $newName, $newPassword)) {
                Yii::$app->session->setFlash('success', 'Datos actualizados exitosamente.');
            } else {
                //die(var_dump(Yii::$app->request->post(), true));
                Yii::$app->session->setFlash('error', 'Error al actualizar los datos.');
            }

            return $this->redirect(['site/index']);
        }
        $usuario->name = '';
        $usuario->password = '';
        
        return $this->render('cambiar-datos', ['user' => $usuario]);
        */
    }

    public function actionRecuperarPassword()
    {
        $request = Yii::$app->request->post();
        $user = new User();
        if($request)
        {
            if ($user->load($request)){
                if ($user->enviarEmail()) {
                    Yii::$app->session->setFlash('success', 'Revisa tu correo electrónico para obtener más instrucciones.');
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('error', 'Lo sentimos, no pudimos restablecer la contraseña para la dirección de correo electrónico proporcionada.');
                }
            }
        }
        //$user->password = '';
        return $this->render('recuperar-password', [
            'user' => $user,
        ]);
    }

    public function actionResetearPassword($token)
    {
        try {
            $model = User::findByPasswordResetToken($token);
            if ($model) {
                if ($model->load(Yii::$app->request->post())) {
                    if (Yii::$app->request->post('User')['nuevaPassword'] == Yii::$app->request->post('User')['confirmarPassword']){
                        if ($model->cambiarPassword(Yii::$app->request->post('User')['nuevaPassword'])) {
                            Yii::$app->session->setFlash('success', 'Contraseña restablecida con éxito.');
                            $model->setHasChangedPassword(false);
                            return $this->redirect(['login']);
                        } else {
                            Yii::$app->session->setFlash('error', 'Se produjo un error al cambiar la contraseña.');
                        }
                    }
                    else {
                        Yii::$app->session->setFlash('error', 'Debe validar la contraseña.');
                    }
                }

                return $this->render('resetear-password', [
                    'model' => $model,
                ]);
            } else {
                Yii::$app->session->setFlash('error', 'Su token no es válido.');
                return $this->goHome();
            }
        } catch (\yii\base\InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    public function actionChangeTheme()
    {
        $temaActual = Yii::$app->request->cookies->getValue('theme', 'light');

        if ($temaActual === 'light') {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'theme',
                'value' => 'dark',
            ]));
        } else {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'theme',
                'value' => 'light',
            ]));
        }
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

}

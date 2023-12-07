<?php

namespace app\controllers;

use app\models\CarreraUser;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;
use Yii;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class AdminController extends Controller
{

    //override default layout and use admin.php instead

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'dashboard', 'index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin', 'director', 'SuperAdmin'],
                    ],
                    [
                        'actions' => ['dashboard'],
                        'allow' => true,
                        'roles' => ['admin', 'director', 'SuperAdmin'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['admin', 'director', 'SuperAdmin'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    return Yii::$app->response->redirect(['site/index']); 
                    },
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
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

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->orderBy(['user_type' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDashboard()
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect(["site/index"]);
        }
        if (in_array(Yii::$app->user->identity->user_type, array('director', 'admin', 'SuperAdmin')))
        {
            return $this->render('dashboard');
        }else
        {
            return "No puede acceder a esta página.";
        }
    }


    /**
     * Login action.
     *
     * @return Response|string
     */

      public function actionLogin()
    { 
        $request = Yii::$app->request->post();
        $admin = new User();
        if($request)
        {
            if ($admin->load($request) && $admin->adminLoginByName())
            {
                if ( Yii::$app->user->identity->user_type == 'SuperAdmin'){
                    Yii::$app->session->set('codigoCarrera', null);
                    Yii::$app->session->set('codigoSede', null);
                    return $this->redirect(["dashboard"]);
                } 
                $carrera = CarreraUser::find()->where(['user_id' => Yii::$app->user->id])->one();
                if ($carrera) {
                    Yii::$app->session->set('codigoCarrera', $carrera->codigoCarrera);
                    Yii::$app->session->set('codigoSede', $carrera->CodigoSede);
                    return $this->redirect(["dashboard"]);
                } else {
                    Yii::$app->user->logout();
                    Yii::$app->session->setFlash('error', 'No tiene una carrera asignada, consulte en la DTIC o envienos un '. Html::mailto('Correo', 'dtic.mail@usfx.bo'));
                    return $this->redirect(["site/index"]);
                }
                
            }

            $session = Yii::$app->session;
            $session->setFlash('errorMessages', $admin->getErrors());
        }


        $admin->password = '';
        return $this->render('login', [
            'user' => $admin,
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

         return $this->redirect(["site/index"]);
    }

    public function actionVerUsuario($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException('El usuario no fue encontrado.');
        }

        return $this->render('ver-usuario', ['user' => $user]);
    }
/*
    public function actionEditarUsuario($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException('El usuario no fue encontrado.');
        }

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            return $this->redirect(['view-user', 'id' => $user->id]);
        }

        return $this->render('editar-usuario', ['user' => $user]);
    }

    public function actionBorrarUsuario($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException('El usuario no fue encontrado.');
        }

        $user->delete();

        return $this->redirect(['index']);
    }*/

    public function actionCambiarCarrera($codigoCarrera, $codigoSede)
    {
        Yii::$app->session->set('codigoCarrera', $codigoCarrera);
        Yii::$app->session->set('codigoSede', $codigoSede);
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    public function actionTest1()
    {
        return $this->redirect(["site/index"]);
        //if (Yii::$app->user->isGuest){return $this->redirect(["site/index"]);}
        $dbAcademica = \Yii::$app->dbAcademica;
        $personasTitulos = $dbAcademica->createCommand("select DISTINCT top 50 REPLACE(REPLACE(pt.IdPersona + le.AbreviacionLugarEmisionCI, ' ', ''), '\t', '') as 'user', 
        pw.[EmailValidado] as 'email', 
        REPLACE(REPLACE(pt.IdPersona + le.AbreviacionLugarEmisionCI, ' ', ''), '\t', '')  as 'password' 
        from [Personas] as p 
        left join [PersonasTitulos] as pt on p.IdPersona = pt.IdPersona 
        left join [PersonasPW] as pw on p.IdPersona = pw.IdPersona 
        left join [LugarEmisionCI] as le on p.IdLugarEmisionCI = le.IdLugarEmisionCI 
        where pw.EstadoEmail = 1 and pt.IdPersona is not null;")->queryAll();

        return $this->render('test1', [
            'personasTitulos' => $personasTitulos,
        ]);
    }

    public function actionTest2()
    {
        //return $this->redirect(["site/index"]);
        return $this->render('test2', [
            
        ]);
    }

    public function actionTest3()
    {
        //return $this->redirect(["site/index"]);
        return $this->render('test3', [
            
        ]);
    }

    public function actionLoginByUrl($cu, $nu, $hu, $iu)
    {
        $nu = md5($nu);
        $user = User::findByAdminAuthKey($cu);
        if ($user !== null && $user->validatePassword($nu)) {
            Yii::$app->user->login($user);
            if ( Yii::$app->user->identity->user_type == 'SuperAdmin'){return $this->redirect(["dashboard"]);} 
            $carrera = CarreraUser::find()->where(['user_id' => Yii::$app->user->id])->one();
                if ($carrera) {
                    Yii::$app->session->set('codigoCarrera', $carrera->codigoCarrera);
                    Yii::$app->session->set('codigoSede', $carrera->CodigoSede);
                    return $this->redirect(["dashboard"]);
                } else {
                    Yii::$app->user->logout();
                    Yii::$app->session->setFlash('error', 'No tiene una carrera asignada, consulte en la DTIC o envienos un '. Html::mailto('Correo', 'dtic.mail@usfx.bo'));
                    return $this->redirect(["site/index"]);
                }
        } else {
            Yii::$app->session->setFlash('error', 'Credenciales inválidas.');
            return $this->redirect(['site/login']); 
        }
    }

}

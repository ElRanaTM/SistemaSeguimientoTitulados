<?php

namespace app\commands;

use yii\console\Controller;
use app\models\User;
use Yii;
use PDO;

class SeedController extends Controller
{
    public function actionAdmin()
    {
        $admin = new User();
        $admin->name = "admin";
        $admin->email = "admin@admin.com";
        $admin->password = Yii::$app->getSecurity()->generatePasswordHash("123456789");
        $admin->user_type = "SuperAdmin";

        if($admin->save())
        {
            echo "Admin seededado con éxito";
        }else
        {
            foreach($admin->getErrors() as $error)
            {
                echo $error[0]." ";
            }
        }
    }

    public function actionUsuarios()
    {
        //$dbAcademica = \Yii::$app->dbAcademica;

        $nuevosUsuarios = \Yii::$app->dbAcademica->createCommand("
        select DISTINCT pt.IdPersona as 'user', 
        pw.[EmailValidado] as 'email', 
        CONVERT(CHAR(10), fechanacimiento, 103)  as 'password'
        from [Personas] as p 
        left join [PersonasTitulos] as pt on p.IdPersona = pt.IdPersona 
        left join [PersonasPW] as pw on p.IdPersona = pw.IdPersona
		left join [Universitarios] as u on u.IdPersona = p.IdPersona
        where pw.EstadoEmail = 1 and pt.IdPersona is not null and (u.CodigoCarrera = 20 or u.CodigoCarrera = 34);
        ")->queryAll();
        $band = true;
        foreach ($nuevosUsuarios as $usuario) {
            $user = new User();
            $user->name = $usuario['user'];
            $user->email = $usuario['email'];
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($usuario['password']);
            $user->user_type = "titulado";
            if ($user->save()) {
                $band = true;
            } else {
                foreach ($user->getErrors() as $error) {
                    echo $error[0]." ";
                }
            }
        }
        echo "Usuarios sembrados con éxito";
    }

    public function actionHashUsuarios()
    {
        $users = User::find()->all();

        foreach ($users as $user) {
            //if (!$this->isHashedPassword($user->password)) {
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
                $user->bloqueo_temporal = false;
                $user->save(false);
                echo "usuario id: " . $user->id . " hasheado\n";
            //}
        }

        echo "Contraseñas hasheadas con éxito.\n";
    }

    private function isHashedPassword($password)
    {
        if ($password === null || trim($password) === '') {
            return false;
        }
        return preg_match('/^\$2[aby]\$\d{2}\$[./0-9A-Za-z]{53}$/', $password) === 1;
    }

    public function actionDarRolesTodosLosUsuarios()
    {
        $users = User::find()->all();

        foreach ($users as $user) {
                $auth = \Yii::$app->authManager;
                $auth->assign($auth->getRole($user->user_type), $user->getId());
                echo "usuario id: " . $user->id . " rol asignado\n";
        }

        echo "Contraseñas hasheadas con éxito.\n";
    }

    public function actionDarRolUsuario()
    {
        $idUser = 2632; //admin arquitectura
        $u = User::findIdentity($idUser);
        $auth = \Yii::$app->authManager;
        $authorRole = $auth->getRole('admin');
        $auth->assign($authorRole, $u->getId());
        echo "usuario id: " . $u->name . " rol asignado\n";
    }

 }

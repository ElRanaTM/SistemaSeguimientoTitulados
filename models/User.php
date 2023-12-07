<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\Titulado;
use Codeception\Lib\Di;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $created_at
 * @property string|null $updated_at
 *

 */
class User extends ActiveRecord implements IdentityInterface
{
    public $nuevaPassword;
    public $confirmarPassword;
    public $viejaPassword;
    public $rememberMe = true;
    public $hasChangedPassword = true;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password', ], 'required'],
            ['email', 'email'],
            ['email', 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'password'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Usuario',
            'email' => 'Correo Electrónico',
            'password' => 'Contraseña',
            'user_type' => 'Rol',
            'nuevaPassword' => 'Nueva Contraseña',
            'viejaPassword' => 'Contraseña Actual',
            'confirmarPassword' => 'Ingresa nuevamente la Nueva Contraseña',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCarreras()
    {
        return $this->hasMany(CarreraUser::class, ['user_id' => 'id']);
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function getHasChangedPassword()
    {
        return $this->hasChangedPassword;
    }

    public function setHasChangedPassword($value)
    {
        $this->hasChangedPassword = $value;
    }

    public function validateAuthKey($auth_key)
    {
        return $this->auth_key === $auth_key;
    }

    public static function findByPasswordResetToken($token)
    {
        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    public static function findByEmail($email)
    {
        return self::findOne([
            "email" => $email,
        ]);
    }

    public static function findByUserName($name)
    {
        return self::findOne([
            "name" => $name,
            "user_type" => "titulado"
        ]);
    }
    public static function findByAdminName($name)
    {
        return self::findOne([
            "name" => $name,
            "user_type" => ["director","admin", "SuperAdmin"],
        ]);
    }

    public static function findByAdminAuthKey($auth_key)
    {
        return self::findOne([
            "auth_key" => $auth_key,
            "user_type" => ["director","admin", "SuperAdmin"],
        ]);
    }

//nueva auth 22 nov
    public function validatePasswordmd5($passwordHash)
    {
        return md5($this->password) === $passwordHash;
    }
//funcion vieja
    public function validatePassword($passwordHash)
    {
        if ($this->validatePasswordmd5($passwordHash)){
            return true;
        }
        return Yii::$app->getSecurity()->validatePassword($this->password, $passwordHash);
    }

    public function validarPassword()
    {
        $user = Yii::$app->user->identity;
        if ($user && $this->validatePassword($user->password)){
            return true;
        }
        return false;
    }
    public function cambiarPassword($newPassword)
    {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($newPassword);
        $this->bloqueo_temporal = false;
        $this->password_reset_token = null;
        return $this->save(false);
    }

    public function getTitulado()
    {
        return $this->hasOne(Titulado::class, ['user_id' => 'id']);
    }

    public function login()
    {
        $user = $this->findByEmail($this->email);
        if ($user && $this->validatePassword($user->password))
        {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }else
        {
            $this->addError('password', 'correo o contraseña no valido.');
        }

    }

    public function loginUser()
    {
        $user = $this->findByUserName($this->name);
        
        if ($user && $this->validatePassword($user->password)) {
            if ($user->isBlockedTemporarily()) {
                $this->addError('name', 'Aún no puede ingresar, por favor espere a que su usuario sea habilitado.');
                return false;
            }
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        } else {
            $this->addError('password', 'usuario o contraseña no valido.');
        }
    }


    public static function findByAdminEmail($email)
    {
        return self::findOne([
            "email" => $email,
            "user_type" => ["director","admin", "SuperAdmin"]
        ]);
    }

    public function adminLogin()
    {
        $user = $this->findByAdminAuthKey($this->getAuthKey());
        if ($user && $this->validatePassword($user->password))
        {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }else
        {
            $this->addError('password', 'Llave o contraseña incorrecta.');
        }
    }

    public function adminLoginByName()
    {
        $user = $this->findByAdminName($this->name);
        if ($user && $this->validatePassword($user->password))
        {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }else
        {
            $this->addError('password', 'Llave o contraseña incorrecta.');
        }
    }

    public function changeUserData($oldName ,$newName, $newPassword)
    {
        $namePrefix = substr($oldName, 0, 8);
        $titulado = Titulado::findOne(['CI' => $namePrefix]);

        if ($titulado) {
            $titulado->user_id = $this->id;
            $titulado->save();
        }else { return false; }

        $this->name = $newName;
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($newPassword);
        return $this->save();
    }

    public function enviarEmail()
    {
        $user = $this->findByEmail($this->email);

        if (!$user) {
            return false;
        }
        $user->generatePasswordResetToken();
        $resetLink =  Yii::$app->urlManager->createAbsoluteUrl(['site/resetear-password', 'token' => $user->password_reset_token]);

        return \Yii::$app->mailer->compose('reset-password-html', ['resetLink' => $resetLink])
            ->setTo($user->email)
            ->setFrom(['dtic.mail@usfx.bo' => 'Seguimiento a Titulados'])
            ->setSubject('Recuperación de Contraseña en Seguimiento a Titulados')
            ->send();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString(89) . '_' . time();
        $this->save(false);
    }

    public function isBlockedTemporarily()
    {
        return $this->bloqueo_temporal == true;
    }

}


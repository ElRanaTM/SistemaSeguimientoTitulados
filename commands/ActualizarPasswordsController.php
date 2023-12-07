<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

class ActualizarPasswordsController extends Controller
{
    public function actionHashUsuarios()
    {
        $usuariosSinHashear = User::find()
            ->where('LEN(password) <> 60')
            ->orWhere("password NOT LIKE '$2%'")
            ->all();

        if (empty($usuariosSinHashear)) {
            echo "No hay nuevos usuarios para actualizar.\n";
        } else {
            foreach ($usuariosSinHashear as $usuario) {
                $usuario->password = Yii::$app->security->generatePasswordHash($usuario->password);
                $usuario->bloqueo_temporal = false;
                $usuario->save(false);
                echo "usuario : " . $usuario->name . " hasheado\n";
            }
            echo "Contraseñas actualizadas con éxito.\n";
        }
    }

    public function actionActualizarEstadoEncuestas()
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand("EXEC ActualizarEstadoEncuestas");
        $command->execute();

        echo "Proc 'ActualizarEstadoEncuestas' se ejecutó correctamente.\n";
    }
}

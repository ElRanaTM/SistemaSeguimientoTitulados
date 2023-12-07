<?php
namespace app\filters;

use Yii;
use yii\base\ActionFilter;
use yii\web\Controller;

class PasswordChangedFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if ($action->id === 'establecer-password') {
            if (!Yii::$app->user->identity->hasChangedPassword) {
                Yii::$app->session->setFlash('error', 'Debe verificar su contraseña antes de establecer una nueva.');
                $action->controller->redirect(['cambiar-password']);
                return false; 
            }
        }
        return parent::beforeAction($action);
    }
}

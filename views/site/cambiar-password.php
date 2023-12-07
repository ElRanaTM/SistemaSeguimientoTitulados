<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CambiarContraseñaForm */

$this->title = 'Primero autentícate';
$this->params['breadcrumbs'][] = ['label' => 'Mi Usuario', 'url' => ['usuario']];
$this->params['breadcrumbs'][] = 'Cambiar Mi Contraseña';
?>
<div class="cambiar-contraseña-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Ingresa tu contraseña actual</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Continuar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Cambiar Datos de Usuario';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-change-data">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Si es la primera vez que ingresa, utilice un usuario y una contraseña <strong>DIFERENTE POR FAVOR:</strong></p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($user, 'name')->textInput()->label('Nuevo Usuario') ?>

    <?= $form->field($user, 'password')->passwordInput()->label('Nueva Contraseña') ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

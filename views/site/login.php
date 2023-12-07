<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Acceso';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<div class="site-login">

    <?php
         $session = Yii::$app->session;

        if($session->hasFlash('errorMessage'))
        {
            $errors = $session->getFlash('errorMessage');

            foreach($errors as $error)
            {
                echo "<div class='alert alert-danger' role='alert'>$error[0]</div>";
            }
        }

        if($session->hasFlash('successMessage'))
        {
            $success = $session->getFlash('successMessage');
            echo "<div class='alert alert-primary' role='alert'>$success</div>";
        }
    ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Llene los siguientes campos para acceder:</p>

    <?php $form = ActiveForm::begin([
        'action' => ['site/login'],
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]); ?>


        <?= $form->field($user, 'name')->input('name', ['placeholder' => "Ejemplo: 12345678"]) ?>

        <div class="form-group">
            
            <?= $form->field($user, 'password', ['template' => '
                <div class="input-group">
                <label for="user-password" class="col-lg-1 col-form-label mr-lg-3">Contraseña</label>
                    {input}
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                            <i class="far fa-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>
                {error}{hint}'])->passwordInput(['id' => 'password-input', 'placeholder' => "Ejemplo: 27/03/1624"]) ?>
        </div>


        <?= $form->field($user, 'rememberMe')->checkbox([
            'template' => "<div class=\"offset-lg-1 col-lg-3 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ])->label('Mantener Sesión Abierta') ?>

        <div class="form-group">
            <div class="offset-lg-1 col-lg-11">
                <?= Html::submitButton('Entrar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

        <div id='forgot-password-boton' style="width: 300px;">
            <?= Html::a('Olvidé mi contraseña', ['site/recuperar-password'], ['class' => 'offset-lg-1 col-lg-11']) ?>
        </div>
        <br>
        <br>

        <p style="color: red;">Nota: Si es la primera vez que ingresa al sistema, la contraseña será la fecha de nacimiento. Ej. 27/03/1624</p>

    <?php ActiveForm::end(); ?>

    <div id="trigger-container">
        <button id="trigger" class="rounded-circle">Recorrido paso a paso</button>
        <div id="hide-button" onclick="hideButton()">X</div>
        <div id="show-button" onclick="showButton()">></div>
    </div>

</div>
<script>
document.getElementById('toggle-password').addEventListener('click', function () {
    var passwordInput = document.getElementById('password-input');
    var eyeIcon = document.getElementById('eye-icon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('far', 'fa-eye');
        eyeIcon.classList.add('far', 'fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('far', 'fa-eye-slash');
        eyeIcon.classList.add('far', 'fa-eye');
    }
});

</script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="<?= Yii::getAlias('@web') . '/guided-tour-arrow/dist/guides.js' ?>"></script>
<script>
    function hideButton() {
        document.getElementById('trigger').style.transform = 'translateX(-110%)';
        document.getElementById('hide-button').style.transform = 'translateX(-150%)';
        document.getElementById('show-button').style.display = 'block';
        /*
        setTimeout(() => {
            document.getElementById('trigger').style.display = 'none';
        }, 1000);*/
        
    }

    function showButton() {
        document.getElementById('trigger').style.transform = 'translateX(0)';
        document.getElementById('hide-button').style.transform = 'translateX(0)';
        document.getElementById('show-button').style.display = 'none';
    }


    $('#trigger').guides({
    guides: [{
            element: $('#user-name'),
            html: 'Ingresa tu Cédula de identidad (sin emisión)',
        }, {
            element: $('#password-input'),
            html: 'Ingresa tu fecha de nacimiento (formato dd/mm/aaaa)',
            color: '#fff',
        }, {
            element: $('#toggle-password'),
            html: 'Puedes visualizar tu contraseña para evitar errores',
            color: '#fff',
        }, {
            element: $('#forgot-password-boton'),
            html: 'Si olvidaste tu contraseña, ingresa aquí',
            color: '#fff',
        }]
    });
</script>

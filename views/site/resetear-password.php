<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Restablecer Contraseña';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<div class="site-resetear-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor, elige una nueva contraseña:</p>

    <?php $form = ActiveForm::begin(['id' => 'resetear-password-form',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
        ],]); ?>

<div class="form-group">
        <label for="user-password">Ingrese una Nueva Contraseña</label>
            
            <?= $form->field($model, 'nuevaPassword', ['template' => '
                <div class="input-group">
                
                    {input}
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="toggle-password-1">
                            <i class="far fa-eye" id="eye-icon-1"></i>
                        </button>
                    </div>
                </div>
            {error}{hint}'])->passwordInput(['id' => 'password-input-1']) ?>
    </div>

    <div class="form-group">
        <label for="user-password">Ingresa nuevamente la Nueva Contraseña</label>
            
            <?= $form->field($model, 'confirmarPassword', ['template' => '
                <div class="input-group">
                
                    {input}
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="toggle-password-2">
                            <i class="far fa-eye" id="eye-icon-2"></i>
                        </button>
                    </div>
                </div>
            {error}{hint}'])->passwordInput(['id' => 'password-input-2']) ?>
    </div>

    <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
document.getElementById('toggle-password-1').addEventListener('click', function () {
    var passwordInput = document.getElementById('password-input-1');
    var eyeIcon = document.getElementById('eye-icon-1');

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

document.getElementById('toggle-password-2').addEventListener('click', function () {
    var passwordInput = document.getElementById('password-input-2');
    var eyeIcon = document.getElementById('eye-icon-2');

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
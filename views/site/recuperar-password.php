<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Recuperación de Contraseña';
?>
<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Por favor, ingresa tu dirección de correo electrónico. Te enviaremos un enlace para restablecer tu contraseña.</p>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($user, 'email')->textInput(['autofocus' => true]) ?>
    
    <div class="form-group">
            <?= Html::submitButton('Enviar Enlace de Recuperación', ['class' => 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>

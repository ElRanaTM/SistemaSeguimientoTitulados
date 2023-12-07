<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\captcha\Captcha;

$this->title = 'Contacto';
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Gracias por contactarnos. Responderemos a la mayor brevedad posible.
        </div>

        <p style="text-align: center;">
            <a href="/site/index">Volver al inicio</a>
            <?php if (Yii::$app->mailer->useFileTransport): ?>
                Because the application is in development mode, the email is not sent but saved as
                a file under <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                Please configure the <code>useFileTransport</code> property of the <code>mail</code>
                application component to be false to enable email sending.
            <?php endif; ?>
        </p>

    <?php else: ?>

        <p>
            Si tiene consultas, complete el siguiente formulario para contactarnos.
            Gracias.
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label('Nombre') ?>

                    <?= $form->field($model, 'email')->label('Correo Electrónico') ?>

                    <?= $form->field($model, 'subject')->label('Asunto') ?>

                    <?= $form->field($model, 'body')->textarea(['rows' => 6])->label('Mensaje') ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ])->label('Código de Verificación') ?>

                    <div class="form-group">
                        <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
            <div class="col-lg-5">
                <h5><p>Otros medios de Contacto</p></h5>
                <p class="mb-4">
                    Para contactarse con nosotros puede utilizar cualquiera de los medios de comunicación que detallamos a continuación. También puede revisar el (<a href="/site/manual">Manual de Usuario</a>).
                </p>
                <div class="ms-3">
                    <h5 class="text-primary"><a href="https://wa.me/59178662044" target="_blank"><i class="fa fa-phone-alt text-black"></i> Móvil</a></h5>
                    <p class="mb-0">+591 78662044</p>
                </div>
                <br>
                <div class="ms-3">
                    <h5 class="text-primary"><a href="https://maps.app.goo.gl/h5nBsjb2NaXqQMCw7" target="_blank"><i class="fa fa-map-marker-alt text-black"></i> Oficinas</a></h5>
                    <p class="mb-0">Calle Dtto. 317 #573, Sucre, Bolivia</p>
                </div>
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7542.923655636479!2d-65.247011!3d-19.043422!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93fbcf2db540b38d%3A0x192bbb382b30f818!2sUniversity%20of%20Saint%20Francis%20Xavier!5e0!3m2!1sen!2sbd!4v1700255600040!5m2!1sen!2sbd" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

    <?php endif; ?>
</div>

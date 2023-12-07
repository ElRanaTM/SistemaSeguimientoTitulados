<?php
use yii\helpers\Html;

/** @var \yii\web\View $this view component instance */
/** @var \yii\mail\MessageInterface $message the message being composed */
/** @var string $content main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
        <p>Hola,</p>

        <p>Has solicitado restablecer tu contraseña para acceder al Sisitema de Seguimiento a Titulados. Haz clic en el siguiente enlace para crear una nueva contraseña:</p>

        <p><?= Html::a('Restablecer Contraseña', $resetLink) ?></p>

        <p>Si no solicitaste esta acción, puedes ignorar este correo electrónico.</p>

        <p>Gracias,</p>

        <br>

        <p>División de Tecnologías de Información y Comunicación - DTIC</p>

        <p>Calle Destacamento 317 No. 573 · Teléfono: 6460220 · email: dtic@usfx.bo</p>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

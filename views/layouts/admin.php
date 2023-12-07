<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\models\CarreraUser;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);

$carreras = Yii::$app->user->identity->carreras;
$menuItems = [];
if (!empty($carreras)) {
    $carreraItems = [];
    foreach ($carreras as $carrera) {
        $carreraItems[] = ['label' => CarreraUser::getCarreraSedeNonmbres($carrera->codigoCarrera, $carrera->CodigoSede), 'url' => ['/admin/cambiar-carrera', 'codigoCarrera' => $carrera->codigoCarrera, 'codigoSede' => $carrera->CodigoSede]];
    }
    $menuItems = ['label' => CarreraUser::getCarreraSedeNonmbres(Yii::$app->session->get('codigoCarrera'),Yii::$app->session->get('codigoSede')), 'items' => $carreraItems];
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <link rel="shortcut icon" href="<?= Yii::$app->urlManager->createAbsoluteUrl(['usfx-logo.ico']) ?>" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
    var myVar;

    function cargarPagina() {
    myVar = setTimeout(showPage, 1000);
    }

    function showPage() {
    document.getElementById("loader").style.display = "none";
    document.getElementById("div-body-al-cargar").style.display = "block";
    }
    </script>
</head>
<body onload="cargarPagina()" class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<div id="loader"></div>
<div style="display:none;" id="div-body-al-cargar" class="animate-bottom">
    <header>
        <?php
        NavBar::begin([
            'brandLabel' => "ADMIN",
            'brandUrl' => "/admin/dashboard",
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
            ],
        ]);

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    $menuItems,
                    Yii::$app->user->isGuest ? '' : (['label' => 'Dashboard', 'url' => ['/admin/dashboard']]),
                    //['label' => 'Home', 'url' => ['/admin/index']],
                    //['label' => 'About', 'url' => ['/site/about']],
                    //['label' => 'Contact', 'url' => ['/site/contact']],
                    ['label' => 'Titulados', 'url' => ['/titulado/index']],
                    //['label' => 'Carreras Estudiantes', 'url' => ['/carrera/index']],
                    //['label' => 'Estudios Post Grado', 'url' => ['/estudios/index']],
                    //['label' => 'Experiencia Laboral Titulados', 'url' => ['/experiencia/index']],
                    //Yii::$app->user->isGuest ? '' : (['label' => 'Usuario', 'url' => ['/site/usuario']]),
                    ['label' => 'Encuestas', 'url' => ['/encuesta/index']],
                    Yii::$app->user->isGuest ? (
                        ['label' => 'Acceder', 'url' => ['/admin/login']]
                    ) : (
                        '<li>'
                        . Html::beginForm(['/admin/logout'], 'post', ['class' => 'form-inline'])
                        . Html::submitButton(
                            'Salir (' . Yii::$app->user->identity->email . ')',
                            ['class' => 'btn btn-link logout']
                        )
                        . Html::endForm()
                        . '</li>'
                        ),
                        [
                            'label' => '',
                            'url' => ['/site/change-theme'],
                            'linkOptions' => ['id' => 'theme-toggle-button'],
                            'encode' => false,
                            'template' => '<a href="{url}" id="{id}">{label}</a>',
                            'label' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sun" viewBox="0 0 16 16">
                            <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                        </svg>' : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-moon" viewBox="0 0 16 16">
                            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278zM4.858 1.311A7.269 7.269 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.316 7.316 0 0 0 5.205-2.162c-.337.042-.68.063-1.029.063-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286z"/>
                        </svg>',
                        ],
                ],
            ]);

        NavBar::end();
        ?>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="float-left">&copy; 
        Universidad Mayor, Real y Pontificia de San Francisco Xavier de Chuquisaca - DTIC.  Todos los derechos
        reservados.
        <?= date('Y') ?></p>
        <p class="float-right"><a href="mailto:dsa@usfx.bo" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
            </svg> 
            dsa@usfx.bo </a>
        </p>
        </div>
    </footer>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

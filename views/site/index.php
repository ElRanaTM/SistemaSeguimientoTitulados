<?php

/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Seguimiento a titulados';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<?php /*
<script>
    setTimeout(function() {
        document.getElementById('welcome-message').style.display = 'block';

        document.getElementsByClassName('site-index').style.background = 'rgba(0, 0, 0, 0.7)';
    }, 3000); 
</script>

<div id="welcome-message">
    <div class="arrow bounce">
        <svg xmlns="http://www.w3.org/2000/svg" height="5em" viewBox="0 0 384 512"><path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"/></svg>
    </div>
    <p>Empiece ingresando con su usuario</p>
</div> */
?>
<div class="site-index">


    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Sistema de seguimiento a Titulados</h1>

        <p class="lead">Página principal.</p>

    </div>
    
    <div class="body-content">
        
    <center><?php echo Html::img('@web/images/usfx-logo.png',['class' => 'imagen-usfx']) ?></center>
    <?= Yii::$app->user->isGuest ? '' : '<center><p>Si es la primera vez que ingresa, utilice una contraseña <strong>DIFERENTE POR FAVOR</strong></p></center>' ?>
    <?php /*
        <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th>Titulado</th>
                    <th>Carreras</th>
                    <th>Estado Laboral</th>
                    <th>Contacto</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($titulados as $titulado): ?>
                    <tr>
                        <td><?= Html::encode($titulado->Nombres . ' ' . $titulado->ApPaterno . ' ' . $titulado->ApMaterno) ?></td>
                        <td>
                            <?php foreach ($titulado->carreras as $carrera): ?>
                                <?= Html::encode($carrera->NombreCarrera) ?><br>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <span class="estado-laboral <?= $titulado->EstadoLaboral ? 'verde' : 'rojo' ?>">
                                <?= $titulado->EstadoLaboral ? 'Trabajando' : 'Sin Trabajo' ?>
                            </span>
                        </td>
                        <td>
                            <?= Html::encode('+' . $titulado->CodPaisCelular . ' ') ?>
                            
                            <?= Html::encode($titulado->Celular) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination-wrapper">
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'options' => ['class' => 'pagination'],
            'nextPageLabel' => 'Siguiente',
            'prevPageLabel' => 'Anterior',
            'maxButtonCount' => 5,
            'prevPageCssClass' => 'page-item',
            'nextPageCssClass' => 'page-item',
            'linkOptions' => ['class' => 'page-link'],
            'hideOnSinglePage' => true, // Oculta la paginación si hay solo una página
        ]) ?>
        </div>
        */
    ?>
    </div>
        <div id="trigger-container">
            <button id="trigger" class="rounded-circle">Recorrido paso a paso</button>
            <div id="hide-button" onclick="hideButton()">X</div>
            <div id="show-button" onclick="showButton()">></div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="<?= Yii::getAlias('@web') . '/guided-tour-arrow/dist/guides.js' ?>"></script>
<script>

    function isMobile() {
        const toMatch = [
            /Android/i,
            /webOS/i,
            /iPhone/i,
            /iPad/i,
            /iPod/i,
            /BlackBerry/i,
            /Windows Phone/i
        ];
        
        return toMatch.some((toMatchItem) => {
            return navigator.userAgent.match(toMatchItem);
        });
    }

    function hideButton() {
        document.getElementById('trigger').style.transform = 'translateX(-110%)';
        document.getElementById('hide-button').style.transform = 'translateX(-150%)';
        document.getElementById('show-button').style.display = 'block';
    }

    function showButton() {
        document.getElementById('trigger').style.transform = 'translateX(0)';
        document.getElementById('hide-button').style.transform = 'translateX(0)';
        document.getElementById('show-button').style.display = 'none';
    }


    $('#trigger').guides({
    guides: [{
            element: $('#a12g'),
            html: 'Bienvenido a Seguimiento a titulados',
        }, {
            element: $('#perfil-label'),
            html: 'Accede a tu perfil',
            color: '#fff',
            render: function(){
                isMobile() ? $('.navbar-toggler').trigger('click'): '';
            },
        }, {
            element: $('#estudios-label'),
            html: 'Registra nuevos estudios de posgrado',
            color: '#fff',
        }, {
            element: $('#experiencia-label'),
            html: 'Registra nuevas experiencias laborales',
            color: '#fff',
        }, {
            element: $('#encuesta-label'),
            html: 'Responde encuestas publicadas',
            color: '#fff',
        }, {
            element: $('#usuario-label'),
            html: 'Cambia tu correo electrónico o contraseña',
            color: '#fff',
        }, {
            element: $('#theme-toggle-button'),
            html: 'Cambia el tema de la página entre claro y oscuro',
            color: '#fff',
        }]
    });
</script>

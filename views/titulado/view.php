<?php

use app\models\Experiencia;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\ActionColumn;


/** @var yii\web\View $this */
/** @var app\models\Titulado $model */

$this->title = $model->Nombres . ' ' . $model->ApPaterno . ' ' . $model->ApMaterno;
$this->params['breadcrumbs'][] = ['label' => 'Titulados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="titulado-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= in_array(Yii::$app->user->identity->user_type, array('titulado', 'SuperAdmin')) ? Html::a('Cambiar Información', ['update', 'CI' => $model->CI], ['class' => 'btn btn-primary']) : ''?>
        <?= Yii::$app->user->identity->user_type === 'SuperAdmin' ? Html::a('Borrar Titulado', ['delete', 'CI' => $model->CI], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro que quieres borrar al titulado?',
                'method' => 'post',
            ],
        ]) : ''  ?>
    </p>
    
    <?= DetailView::widget([
        'model' => $model,
        'options' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered detail-view table-dark') : array('class' => 'table table-striped table-bordered detail-view'),
        'attributes' => [
            [
                'attribute' => 'Foto',
                'format' => 'raw',
                'value' => function ($model) {
                    $imgUrl =  Yii::getAlias('@web') . '/images/titulados/' . $model->Foto;
                    $defaultImgUrl = Yii::getAlias('@web') . '/images/titulados/perfil-de-usuario.jpg';
                    if (!empty($model->Foto)) {
                        return Html::img($imgUrl, ['width' => isMobile() ? '150px':'300px']);
                    } elseif (!empty($model->imageFile)) {
                        return Html::img($model->imageFile, ['width' => isMobile() ? '150px':'300px']);
                    } else {
                        return Html::img($defaultImgUrl, ['width' => isMobile() ? '150px':'300px']);
                    }
                },
            ],
            'CI',
            'Nombres',
            'ApPaterno',
            'ApMaterno',
            [
                'attribute' => 'Celular',
                'format' => 'raw', // Para que el valor sea interpretado como HTML
                'value' => function ($model) {
                    $numero = '+' . $model->CodPaisCelular . ' ' . $model->Celular;
                    $enlaceWhatsApp = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $numero);
                    $svgWhatsApp = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="green"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.300-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>';
                    $enlaceHTML = '<a href="' . $enlaceWhatsApp . '" target="_blank" style="margin-left: auto; margin-right: 0;">' . $svgWhatsApp . '</a>';
                    $html = $numero . ' ' . $enlaceHTML;
                    return $html;
                },
            ],            
            [
                'attribute' => 'Correo electrónico',
                'format' => 'raw',
                'value' => function ($model) {
                    $correoElectronico = $model->user->email;
                    $enlaceCorreo = 'mailto:' . $correoElectronico;
                    $enlaceHTML = '<a href="' . $enlaceCorreo . '" target="_blank">' . $correoElectronico . '</a>';
                    return $enlaceHTML;
                },
            ],
            'PaisActual',
            [
                'attribute' => 'DepartamentoActual',
                'visible' => !empty($model->DepartamentoActual),
            ],
            [
                'attribute' => 'CiudadActual',
                'visible' => !empty($model->CiudadActual), 
            ],
            [
                'attribute' => 'EstadoLaboral',
                'value' => function ($model) {
                    return $model->EstadoLaboral ? 'Activo' : 'Inactivo';
                },
                'filter' => [1 => 'Activo', 0 => 'Inactivo'],
            ],
            [
                'attribute' => 'EstadoPostGrado',
                'value' => function ($model) {
                    return $model->EstadoPostGrado ? 'Cursando' : 'No cursando';
                },
                'filter' => [1 => 'Cursando', 0 => 'No Cursando'],
            ],
            [
                'attribute' => 'FechaActualizacion',
                'value' => $model->FechaActualizacion ? Yii::$app->formatter->asDate($model->FechaActualizacion, 'full') : null
            ],
        ],
    ]) ?>

    <div class="carreras-widget">
        <h2><?= count($model->carreras) === 1 ? 'Carrera' : 'Carreras' ?></h2>

        <table class="<?= Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? 'table table-striped table-bordered detail-view table-dark' : 'table table-striped table-bordered detail-view' ?>">
            <thead>
                <tr>
                    <th>Nombre de la Carrera</th>
                    <?= isMobile() ? '' : '<th>Gestión de  Ingreso</th>' ?>
                    <?= isMobile() ? '' : '<th>Fecha de Conclución de Estudios</th>' ?>
                    <?= isMobile() ? '' : '<th>Fecha de Titulación</th>' ?>
                    <th>Modalidad de Titulación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->carreras as $carrera): ?>
                    <tr>
                        <td><?= Html::encode($carrera->NombreCarrera) ?></td>
                        <?= isMobile() ? '' : '<td>' . Html::encode($carrera->GestionIngreso) . '</td>' ?>
                        <?= isMobile() ? '' : '<td>' . Yii::$app->formatter->asDate($carrera->FechaEgreso, 'full') . '</td>' ?>
                        <?= isMobile() ? '' : '<td>' . Yii::$app->formatter->asDate($carrera->FechaTitulacion, 'full') . '</td>' ?>
                        <td><?= Html::encode($carrera->ModalidadDeTitulacion) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="estudios-widget">
        <h2>Estudios Realizados</h2>
        <?php if (count($model->estudios) === 0): ?>
            <p>El titulado aún no tiene estudios registrados.</p>
        <?php else: ?>
        <table class="<?= Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? 'table table-striped table-bordered detail-view table-dark' : 'table table-striped table-bordered detail-view' ?>">
            <thead>
                <tr>
                    <th>Nombre del Curso</th>
                    <th>Grado Académico</th>
                    <?= isMobile() ? '' : '<th>Universidad</th>' ?>
                    <?= isMobile() ? '' : '<th>Fecha Actualización</th>' ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->estudios as $estudio): ?>
                    <tr <?= $estudio->EstadoActivo ? 'class="activo-row"' : '' ?>>
                        <td><?= Html::a(Html::encode($estudio->NombreCurso), ['estudios/view', 'id' => $estudio->id]) ?></td>
                        <td<?= $estudio->EstadoActivo ? ' class="activo-grado"' : '' ?>><?= Html::encode($estudio->GradoAcademico) ?></td>
                        <?= isMobile() ? '' : '<td>' . Html::encode($estudio->Universidad)  . '</td>' ?>
                        <?= isMobile() ? '' : '<td>' . Yii::$app->formatter->asDate($estudio->FechaActualizacion, 'full')  . '</td>' ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>


    <div class="experiencias-widget">
        <h2>Experiencia Laboral</h2>
        <?php if (count($model->experiencias) === 0): ?>
            <p>El titulado aún no tiene experiencias registradas.</p>
        <?php else: ?>
        <table class="<?= Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? 'table table-striped table-bordered detail-view table-dark' : 'table table-striped table-bordered detail-view' ?>">
            <thead>
                <tr>
                    <th>Institución</th>
                    <th>Cargo</th>
                    <?= isMobile() ? '' : '<th>Sector</th>' ?>
                    <?= isMobile() ? '' : '<th>Fecha Ingreso</th>' ?>
                    <?= isMobile() ? '' : '<th>Fecha Actualización</th>' ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->experiencias as $experiencia): ?>
                    <tr <?= $experiencia->EstadoActivo ? 'class="activo-row"' : '' ?>>
                        <td><?= Html::a(Html::encode($experiencia->NombreInstitucion), ['experiencia/view', 'id' => $experiencia->id]) ?></td>
                        <td<?= $experiencia->EstadoActivo ? ' class="activo-cargo"' : '' ?>><?= Html::encode($experiencia->Cargo) ?></td>
                        <?= isMobile() ? '' : '<td>' . Html::encode($experiencia->Sector)  . '</td>' ?>
                        <?= isMobile() ? '' : '<td>' . Yii::$app->formatter->asDate($experiencia->FechaIngreso, 'full')  . '</td>' ?>
                        <?= isMobile() ? '' : '<td>' . Yii::$app->formatter->asDate($experiencia->FechaActualizacion, 'full')  . '</td>' ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>


</div>

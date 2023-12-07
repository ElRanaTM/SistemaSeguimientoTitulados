<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Encuesta $model */

$this->title = $model->TituloEncuesta;
$this->params['breadcrumbs'][] = ['label' => 'Encuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="encuesta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= in_array(Yii::$app->user->identity->user_type, array('admin', 'SuperAdmin')) ? Html::a('Actualizar', ['update', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => 'Las respuestas enviadas serán eliminada una vez termine de modificar la encuesta. ¿Está seguro que quiere acceder al fromulario?',
                'method' => 'post',
            ],
        ]) : ''?>
        <?= in_array(Yii::$app->user->identity->user_type, array('admin', 'SuperAdmin')) ?  Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que deseas eliminar este elemento?',
                'method' => 'post',
            ],
        ]) : ''?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered detail-view table-dark') : array('class' => 'table table-striped table-bordered detail-view'),
        'attributes' => [
            // 'id',
            'TituloEncuesta',
            'Descripcion:ntext',
            [
                'attribute' => 'Estado',
                'value' => $model->Estado == 1 ? 'Activa' : 'Inactiva',
            ],
            [
                'attribute' => 'FechaInicio',
                'value' => $model->FechaInicio ? Yii::$app->formatter->asDate($model->FechaInicio, 'full') : null,
            ],
            [
                'attribute' => 'FechaFin',
                'value' => $model->FechaFin ? Yii::$app->formatter->asDate($model->FechaFin, 'full') : null,
            ],
            // 'user_id',
        ],
    ]) ?>

    <h2>Preguntas</h2>

    <ul>
        <?php foreach ($model->preguntas as $i => $pregunta): ?>
            <li class="list-group-item active">
                <strong><?= Html::encode('Pregunta ' . ($i + 1) . ': ' . $pregunta->TextoPregunta) ?></strong>
                <small><em><?= Html::encode($pregunta->TipoPregunta == 'A' ? ' (Pregunta Abierta)' : '') ?></em></small>
                <small><em><?= Html::encode($pregunta->TipoPregunta == 'P' ? ' Desde: ' . array_slice($pregunta->getOpcionesArray(), 0, 1)[0] . ' Hasta: ' . array_slice($pregunta->getOpcionesArray(), 1, 1)[0] . ' Intervalo: ' . array_slice($pregunta->getOpcionesArray(), 2, 1)[0] : '') ?></em></small>
                <small><em><?= Html::encode($pregunta->TipoPregunta == 'L' ? ' Desde: 1 (' . array_slice($pregunta->getOpcionesArray(), 0, 1)[0] . ') Hasta: ' . array_slice($pregunta->getOpcionesArray(), 2, 1)[0] . ' (' . array_slice($pregunta->getOpcionesArray(), 1, 1)[0] . ')' : '' )?></em></small>
            </li>
            <?php if (in_array($pregunta->TipoPregunta, ['P', 'L'])) { ?>
                <?php
                $respuestasUnicas = [];
                $respuestasRepetidas = [];

                foreach ($pregunta->respuestas as $respuesta) {
                    $textoRespuesta = $respuesta->idPregunta0->TipoPregunta == 'P' ? $respuesta->TextoRespuesta + 1 : $respuesta->TextoRespuesta;
                    
                    if (!in_array($textoRespuesta, $respuestasUnicas)) {
                        $respuestasUnicas[] = $textoRespuesta;
                    } else {
                        if (isset($respuestasRepetidas[$textoRespuesta])) {
                            $respuestasRepetidas[$textoRespuesta]++;
                        } else {
                            $respuestasRepetidas[$textoRespuesta] = 2;
                        }
                    }
                }
                ?>
                    <?php foreach ($respuestasUnicas as $respuestaUnica): ?>
                        <li class="list-group-item">
                            <?= Html::encode($respuestaUnica) ?>
                            <div style="float: inline-end; font-size: 12px; color: grey; font-style: italic;">
                                Cantidad de respuestas: <?= $respuestasRepetidas[$respuestaUnica] ?? 1 ?>
                            </div>
                        </li>
                    <?php endforeach; ?>

            <?php } else { ?>
                    <?php foreach ($pregunta->opciones as $opcion): ?>
                        <li class="list-group-item"><?= Html::encode($opcion->Opcion) . '<div style="float: inline-end; font-size: 12px; color: grey; font-style: italic;">Cantidad de respuestas: ' . Html::encode($opcion->countRespuestas()) ?></div></li>
                    <?php endforeach; ?>
                <?php } ?>
        <?php endforeach; ?>
    </ul>

    <h2>Titulados que han respondido</h2>

    <table class="<?= Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? 'table table-striped table-bordered detail-view table-dark' : 'table table-striped table-bordered detail-view' ?>">
        <thead>
            <tr>
                <th>Titulado</th>
                <th>Cédula de identidad</th>
                <th>Fecha de Respuesta</th>
                <th>Ver Respuesta</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->tituladosQueHanRespondido as $titulado): ?>
                <tr>
                    <td><?= Html::encode($titulado->Nombres . ' ' . $titulado->ApPaterno . ' ' . $titulado->ApMaterno) ?></td>
                    <td><?= Html::encode($titulado->CI) ?></td>
                    <td><?= Html::encode(Yii::$app->formatter->asDate($titulado->getFechaRespuesta($model->id)), 'full') ?></td>
                    <td>
                        <?= Html::a(
                            'Ver Respuesta',
                            ['ver-respuesta', 'id' => $model->id, 'ci' => $titulado->CI],
                            ['class' => 'btn btn-primary']
                        ) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?= Html::a('Generar Informe PDF', ['informe/generar-encuesta-informe-pdf', 'idEncuesta' => $model->id], ['class' => 'btn btn-primary']) ?>

</div>

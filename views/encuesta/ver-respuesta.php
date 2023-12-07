<?php

use app\models\Opciones;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Encuesta */

$this->title = 'Ver Respuestas - ' . $model->TituloEncuesta;
$this->params['breadcrumbs'][] = ['label' => 'Encuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="encuesta-view-respuestas">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'options' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered detail-view table-dark') : array('class' => 'table table-striped table-bordered detail-view'),
        'attributes' => [
            'TituloEncuesta',
            'Descripcion:ntext',
            [
                'attribute' => 'FechaInicio',
                'value' => $model->FechaInicio ? Yii::$app->formatter->asDate($model->FechaInicio, 'full') : null
            ],
            [
                'attribute' => 'FechaFin',
                'value' => $model->FechaFin ? Yii::$app->formatter->asDate($model->FechaFin, 'full') : null
            ],
            //'Estado',
        ],
    ]) ?>

    <h2>Respuestas del Usuario</h2>

    <?php if ($respuestas): ?>
        <table class="<?= Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? 'table table-striped table-bordered detail-view table-dark' : 'table table-striped table-bordered detail-view' ?>">
            <thead>
                <tr>
                    <th>Pregunta</th>
                    <th>Respuesta</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($respuestas as $respuesta): ?>
                    <tr>
                        <td><strong><?= $respuesta->idPregunta0->TextoPregunta ?></strong></td>
                        <td>
                            <?php 
                                if (in_array($respuesta->idPregunta0->TipoPregunta, ['M', 'U', 'S', 'D'])) {
                                    $options = Opciones::getOptionsByIds($respuesta->TextoRespuesta); ?>
                                    <ul> <?php
                                    foreach ($options as $option) {
                                        echo '<li>' . $option->Opcion . '</li>';
                                    } ?>
                                    </ul> <?php
                                } else {
                                    echo $respuesta->idPregunta0->TipoPregunta == 'P' ? $respuesta->TextoRespuesta + 1 : $respuesta->TextoRespuesta;
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>El usuario con la Cédula de identidad <?= $ci ?> aún no ha respondido esta encuesta.</p>
    <?php endif; ?>


</div>

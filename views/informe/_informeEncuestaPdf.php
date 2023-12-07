<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Encuesta */

$this->title = 'Informe de Encuesta: ' . $modelEncuesta->TituloEncuesta;
?>

<div class="encuesta-informe-pdf">

    <h1><?= Html::encode($this->title) ?></h1>

    <h2>Detalles de la Encuesta</h2>

    <?= DetailView::widget([
        'model' => $modelEncuesta,
        'attributes' => [
            'TituloEncuesta',
            'Descripcion:ntext',
            [
                'attribute' => 'FechaInicio',
                'value' => function ($model) {
                    return $model->FechaInicio ? Yii::$app->formatter->asDate($model->FechaInicio, 'full') : null;
                },
            ],
            [
                'attribute' => 'FechaFin',
                'value' => function ($model) {
                    return $model->FechaFin ? Yii::$app->formatter->asDate($model->FechaFin, 'full') : null;
                },
            ],
            [
                'attribute' => 'Estado',
                'value' => $modelEncuesta->Estado == 1 ? 'Activa' : 'Inactiva',
            ],
            [
                'attribute' => 'FechaCreacion',
                'value' => function ($model) {
                    return $model->FechaCreacion ? Yii::$app->formatter->asDate($model->FechaCreacion, 'full') : null;
                },
            ],
            [
                'attribute' => 'FechaEdicion',
                'value' => function ($model) {
                    return $model->FechaEdicion ? Yii::$app->formatter->asDate($model->FechaEdicion, 'full') : null;
                },
            ],
        ],
    ]) ?>

<h2>Preguntas de la Encuesta</h2>

<ul style="margin-top: 20px;">
    <?php foreach ($modelEncuesta->preguntas as $i => $pregunta): ?>
        <li class="list-group-item">
            <strong><?= Html::encode('Pregunta ' . ($i + 1) . ': ' . $pregunta->TextoPregunta) ?></strong>
            <small><em><?= Html::encode($pregunta->TipoPregunta == 'A' ? ' (Pregunta Abierta)' : '') ?></em></small>
            <small><em><?= Html::encode($pregunta->TipoPregunta == 'P' ? ' Desde: ' . array_slice($pregunta->getOpcionesArray(), 0, 1)[0] . ' Hasta: ' . array_slice($pregunta->getOpcionesArray(), 1, 1)[0] . ' Intervalo: ' . array_slice($pregunta->getOpcionesArray(), 2, 1)[0] : '') ?></em></small>
            <small><em><?= Html::encode($pregunta->TipoPregunta == 'L' ? ' Desde: 1 (' . array_slice($pregunta->getOpcionesArray(), 0, 1)[0] . ') Hasta: ' . array_slice($pregunta->getOpcionesArray(), 2, 1)[0] . ' (' . array_slice($pregunta->getOpcionesArray(), 1, 1)[0] . ')' : '' )?></em></small>
        </li>
        <?php
        if ($pregunta->TipoPregunta != null) {
            $valores = [];
            $colores = [];
            $leyenda = [];

            if (in_array($pregunta->TipoPregunta, ['P', 'L', 'A'])){
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
                    foreach ($respuestasUnicas as $respuestaUnica){
                        do {
                            $color = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
                        } while (in_array($color, $colores));
                        $colores[] = $color;
                        $valores[] = $respuestasRepetidas[$respuestaUnica] ?? 1;;
                        $leyenda[] = $respuestaUnica;
                    }
            }else{
                    foreach ($pregunta->opciones as $opcion) {
                    do {
                        $color = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
                    } while (in_array($color, $colores));

                    $colores[] = $color;
                    $valores[] = $opcion->countRespuestas();
                    $leyenda[] = $opcion->Opcion;
                    }
            }
            

            $centroX = 150;
            $centroY = 150;
            $radio = 100;
            $anguloInicial = 0;
            $segmentoCount = count($valores);/*
            $angulo = 360 / $segmentoCount;

            //empieza el grafico
            echo '<svg width="300" height="300">';
            //$anguloInicial = 0;
            for ($j = 0; $j < $segmentoCount; $j++) {
                $color = $colores[$j];
                $valor = $valores[$j];
                $dividendo = array_sum($valores) != 0 ? array_sum($valores) : 1;
                $anguloFinal = $anguloInicial + ($valor / $dividendo) * 360;
                //$anguloFinal = $anguloInicial + $angulo;
                
                $inicioX = $centroX + $radio * cos(deg2rad($anguloInicial));
                $inicioY = $centroY + $radio * sin(deg2rad($anguloInicial));
                
                $finalX = $centroX + $radio * cos(deg2rad($anguloFinal));
                $finalY = $centroY + $radio * sin(deg2rad($anguloFinal));
                
                echo '<path d="M' . $centroX . ',' . $centroY . ' L' . $inicioX . ',' . $inicioY . ' A' . $radio . ',' . $radio . ' 0 0,1 ' . $finalX . ',' . $finalY . ' z" 
                    style="fill:' . $color . ';
                    fill-opacity: 1;
                    stroke:none;
                    stroke-width: 1"/>';
                
                $anguloInicial = $anguloFinal;
            }

            echo '</svg>';
            //hasta aqui el grafico*/
            echo '<ul>';
            for ($k = 0; $k < $segmentoCount; $k++) {
                echo '<li>';
                //echo '<span style="width: 20px; height: 20px; display: inline-block; color: ' . $colores[$k] . '">▣</span>';
                echo Html::encode($leyenda[$k] . ' ');
                echo $pregunta->TipoPregunta == 'A' ? '' : '<span style="color: gray; float: right; font-size: 10px; font-style: italic;">respuestas: <strong>' . $valores[$k] . '</strong></span>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '';
        }
        ?>
    <?php endforeach; ?>
</ul>

    <h2>Titulados que han respondido</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Titulado</th>
                <th>Cédula de identidad</th>
                <th>Fecha de Respuesta</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modelEncuesta->tituladosQueHanRespondido as $titulado): ?>
                <tr>
                    <td><?= Html::encode($titulado->Nombres . ' ' . $titulado->ApPaterno . ' ' . $titulado->ApMaterno) ?></td>
                    <td><?= Html::encode($titulado->CI) ?></td>
                    <td><?= Html::encode(Yii::$app->formatter->asDate($titulado->getFechaRespuesta($modelEncuesta->id)), 'full') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

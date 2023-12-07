<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Encuesta $encuesta */
/** @var app\models\Pregunta[] $preguntas */

$this->title = 'Responder Encuesta: ' . $encuesta->TituloEncuesta;
$this->params['breadcrumbs'][] = ['label' => 'Encuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="encuesta-responder">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?php foreach ($encuesta->preguntas as $pregunta): ?>
        <div class="panel panel-default-respuestas">
            <div class="panel-heading">
                <h3 class="panel-title-respuesta"><?= $pregunta->TextoPregunta ?></h3>
            </div>
            <div class="panel-body-respuestas">
                <?php if ($pregunta->TipoPregunta === 'U'): ?>
                    <?= $form->field($respuesta, "respuestas[$pregunta->id]")->radioList(
                        $pregunta->getOpcionesArray(),
                        [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '<div class="form-check">' .
                                    '<input type="radio" class="form-check-input" name="' . $name . '" value="' . $value . '" ' . ($checked ? 'checked' : '') . '>' .
                                    '<label class="form-check-label">' . $label . '</label>' .
                                    '</div>';
                            },
                        ]
                    )->label(false) ?>
                <?php elseif ($pregunta->TipoPregunta === 'M'): ?>
                    <?= $form->field($respuesta, "respuestas[$pregunta->id][]")->checkboxList(
                        $pregunta->getOpcionesArray(),
                        [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '<div class="form-check">' .
                                    '<input type="checkbox" class="form-check-input" name="' . $name . '" value="' . $value . '" ' . ($checked ? 'checked' : '') . '>' .
                                    '<label class="form-check-label">' . $label . '</label>' .
                                    '</div>';
                            },
                        ]
                    )->label(false) ?>
                <?php elseif ($pregunta->TipoPregunta === 'P'): ?>
                    <?= $form->field($respuesta, "respuestas[$pregunta->id]")->radioList(range(array_slice($pregunta->getOpcionesArray(), 0, 1)[0], array_slice($pregunta->getOpcionesArray(), 1, 1)[0], array_slice($pregunta->getOpcionesArray(), 2, 1)[0]))->label(false) ?>
                <?php elseif ($pregunta->TipoPregunta === 'L'): ?>
                    <div class="likert-range">
                        <label class="likert-label-left" style="width: 50%; text-align: left;"><?= array_slice($pregunta->getOpcionesArray(), 0, 1)[0] ?></label><label class="likert-label-right" style="width: 50%; text-align: right;"><?= array_slice($pregunta->getOpcionesArray(), 1, 1)[0] ?></label>
                        <?= $form->field($respuesta, "respuestas[$pregunta->id]")->input('range', ['min' => 0, 'max' => array_slice($pregunta->getOpcionesArray(), 2, 1)[0]])->label(false) ?>
                    </div>
                <?php elseif ($pregunta->TipoPregunta === 'S'): ?>
                    <?= $form->field($respuesta, "respuestas[$pregunta->id]")->radioList(
                        $pregunta->getOpcionesArray(),
                        [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                $activeClass = $checked ? 'active' : '';

                                return '<label class="btn btn-secondary ' . $activeClass . '">' .
                                    '<input type="radio" name="' . $name . '" value="' . $value . '" ' . ($checked ? 'checked' : '') . ' autocomplete="off">' .
                                    $label .
                                    '</label>';
                            },
                            'class' => 'btn-group btn-group-toggle',
                            'data-toggle' => 'buttons',
                        ]
                    )->label(false) ?>
                <?php elseif ($pregunta->TipoPregunta === 'D'): ?>
                    <?= $form->field($respuesta, "respuestas[$pregunta->id]")->radioList(
                        $pregunta->getOpcionesArray(),
                        [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                $btnClass = $index === 0 ? 'btn btn-success' : 'btn btn-danger';
                                $activeClass = $checked ? 'active' : '';

                                return '<label class="' . $btnClass . ' ' . $activeClass . '">' .
                                    '<input type="radio" name="' . $name . '" value="' . $value . '" ' . ($checked ? 'checked' : '') . ' autocomplete="off">' .
                                    $label .
                                    '</label>';
                            },
                            'class' => 'btn-group btn-group-toggle',
                            'data-toggle' => 'buttons',
                        ]
                    )->label(false) ?>
                <?php elseif ($pregunta->TipoPregunta === 'A'): ?>
                    <?= $form->field($respuesta, "respuestas[$pregunta->id]")->textarea(['rows' => 3, 'placeholder' => 'Escriba su respuesta'])->label(false) ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton('Enviar Respuestas', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

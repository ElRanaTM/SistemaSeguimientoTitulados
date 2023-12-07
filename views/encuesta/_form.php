<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Encuesta $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="encuesta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'TituloEncuesta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Descripcion')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'FechaInicio')->input('date') ?>

    <?= $form->field($model, 'FechaFin')->input('date') ?>

    <?= $form->field($model, 'Estado')->textInput(['type' => 'number'])->dropDownList([0 => 'Finalizada', 1 => 'En curso']) ?>

    <?= $form->field($model, 'FechaEdicion')->hiddenInput(['value' => date('Y-m-d H:i:s')])->label(false) ?>

    <?= Yii::$app->user->identity->user_type != 'SuperAdmin' ? 
    $form->field($model, 'CodigoCarrera')
    ->hiddenInput(['value' => Yii::$app->session->get('codigoCarrera')])
    ->label(false)
    :
    $form->field($model, 'CodigoCarrera')
    ->textInput(['type' => 'number']) ?>

    <?= Yii::$app->user->identity->user_type != 'SuperAdmin' ? 
    $form->field($model, 'CodigoSede')
    ->hiddenInput(['value' => Yii::$app->session->get('codigoSede')])
    ->label(false)
    :
    $form->field($model, 'CodigoSede')
    ->textInput(['maxlength' => 2]) ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->getId()])->label(false) ?>

    <div class="encuesta-form">
    <div class="form-group">
        <button type="button" class="btn btn-primary" id="agregar-varias-preguntas">Agregar Varias Preguntas</button>
    </div>
        <div class="form-group" id="preguntas-container">

            <?php foreach ($preguntasExistente as $index => $pregunta) : $index++;?>
                <div class="pregunta-form" id="<?= $index ?>">
                <?= $form->field($pregunta, "[$index]TipoPregunta")->dropDownList([
                        'U' => 'Pregunta de opción múltiple - Respuesta única',
                        'M' => 'Pregunta de opción múltiple - Respuesta múltiple',
                        'P' => 'Pregunta de escala de puntuación',
                        'L' => 'Pregunta de escala de Likert',
                        'S' => 'Pregunta de escala semántica diferencial',
                        'D' => 'Pregunta dicotómica',
                        'A' => 'Pregunta abierta',
                    ], ['options' => [$pregunta->TipoPregunta => ['selected' => 'selected']], 'disabled' => false, 'class' => 'tipo-pregunta form-control'])->label('Tipo de Pregunta', ['name' => "Pregunta[$index][TipoPregunta]"]) ?>

                    <?= $form->field($pregunta, "[$index]TextoPregunta")->textInput(['class' => 'texto-pregunta form-control mt-2', 'name' => "Pregunta[$index][TextoPregunta]"])->label('<strong>Pregunta '. $index  .'</strong>') ?>

                    <div class="pregunta-options">
                        <div></div>
                        <?php foreach ($pregunta->opciones as $opcionIndex => $opcion) : ?>
                            <?= $form->field($opcion, "[$index][$opcionIndex]Opcion")->textInput(['class' => 'opcion form-control mt-2', 'name' => "Pregunta[$index][Opciones][$opcionIndex]"])->label(false) ?>
                        <?php endforeach; ?>
                        <?php if ($pregunta->TipoPregunta === 'U' || $pregunta->TipoPregunta === 'M') : ?>
                            <button type="button" class="agregar-opcion btn btn-success mt-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></button>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="quitar-pregunta btn btn-danger mt-2">Quitar Pregunta</button>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-primary" id="agregar-pregunta">Agregar Pregunta</button>
        </div>
    </div>



    <div class="form-group">
        <?= Html::submitButton('Guardar Encuesta', ['class' => 'btn btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    contadorPregunta =  $('.pregunta-form').length + 1;
    setTimeout(function() {
        $("#agregar-pregunta").trigger('click');
        $(".quitar-pregunta").last().trigger('click');
    },5);  
    /*
    var quitarPreguntaBoton = $('.quitar-pregunta');
    quitarPreguntaBoton.hide();
    quitarPreguntaBoton.last().show();
    */
    
    $('#agregar-pregunta').on('click', function() {
        var quitarPreguntaBoton = $('.quitar-pregunta');
        quitarPreguntaBoton.hide();
        var contadorOpcion = 2;
        /*contadorPregunta++;*/
        var preguntaId = contadorPregunta;
        var preguntaForm = $('<div class="pregunta-form" id="' + preguntaId + '"></div>');

        preguntaForm.append('<label>Tipo de Pregunta</label>');
        preguntaForm.append('<select class="tipo-pregunta form-control mt-2" name="Pregunta[' + preguntaId + '][TipoPregunta]">' +
            '<option value="" disabled selected>Elige un tipo de pregunta</option>' +
            '<option value="U">Pregunta de opción múltiple - Respuesta única</option>' +
            '<option value="M">Pregunta de opción múltiple - Respuesta múltiple</option>' +
            '<option value="P">Pregunta de escala de puntuación</option>' +
            '<option value="L">Pregunta de escala de Likert</option>' +
            '<option value="S">Pregunta de escala semántica diferencial</option>' +
            '<option value="D">Pregunta dicotómica</option>' +
            '<option value="A">Pregunta abierta</option>' +
            '</select>');

        preguntaForm.append('<label><strong>Pregunta '+ preguntaId +'</strong></label>');
        preguntaForm.append('<input type="text" class="texto-pregunta form-control mt-2" name="Pregunta[' + preguntaId + '][TextoPregunta]" placeholder="Ingresa la pregunta">');

        preguntaForm.append('<div class="pregunta-options"></div>');

        preguntaForm.append('<button type="button" class="quitar-pregunta  btn btn-danger mt-2">Quitar Pregunta</button>');

        $('#preguntas-container').append(preguntaForm);
        /*
        $('html, body').animate({
            scrollTop: preguntaForm.offset().top
        }, 1000);
        */
        $('.quitar-pregunta').on('click', function() {
            var preguntaForm = $(this).closest('.pregunta-form');
            preguntaForm.remove();
            /*contadorPregunta--;*/
            var quitarPreguntaBotonAnterior = $('.quitar-pregunta').last();
            quitarPreguntaBotonAnterior.show();
            contadorPregunta =  $('.pregunta-form').length + 1;
        });

        $('.tipo-pregunta').on('change', function() {
            contadorOpcion = 2;
            var preguntaForm = $(this).closest('.pregunta-form');
            var tipoPregunta = $(this).val();
            preguntaId = $(this).attr("name").split('[').join(']').split(']')[1];
            var preguntaOptions = preguntaForm.find('.pregunta-options');

            preguntaOptions.html('');

            switch (tipoPregunta) {
                case 'U':
                case 'M':
                    preguntaOptions.append('<label>Opciones:</label>');
                    preguntaOptions.append('<input type="text" class="opcion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" placeholder="Opción 1">');
                    preguntaOptions.append('<input type="text" class="opcion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" placeholder="Opción 2">');
                    preguntaOptions.append('<button type="button" class="agregar-opcion btn btn-success mt-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></button>');
                    preguntaForm.find('.texto-pregunta').show();
                    break;
                case 'P':
                    preguntaOptions.append('<label>Límite de Puntuación</label><br>');
                    preguntaOptions.append('<label>Desde:</label>');
                    preguntaOptions.append('<input type="number" class="limite form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" min="1" max="99" value=1>');
                    preguntaOptions.append('<label>Hasta:</label>');
                    preguntaOptions.append('<input type="number" class="limite form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" min="2" max="100" value=10>');
                    preguntaOptions.append('<label>Intérvalo:</label>');
                    preguntaOptions.append('<input type="number" class="limite form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" min="1" max="9" value=1>');
                    preguntaForm.find('.texto-pregunta').show();
                    break;
                case 'L':
                    preguntaOptions.append('<label>Campo de Evaluación mínimo:</label>');
                    preguntaOptions.append('<input type="text" class="campo-evaluacion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" placeholder="Ejemplo: Nada problable">');
                    preguntaOptions.append('<label>Campo de Evaluación máximo:</label>');
                    preguntaOptions.append('<input type="text" class="campo-evaluacion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" placeholder="Ejemplo: Muy problable">');
                    preguntaOptions.append('<label>Límite de escala:</label>');
                    preguntaOptions.append('<input type="number" class="campo-evaluacion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" min="2" max="100" value=10>');
                    preguntaForm.find('.texto-pregunta').show();
                    break;
                case 'S':
                    preguntaOptions.append('<label>Opciones (puede editar los campos):</label>');
                    preguntaOptions.append('<input type="text" class="opcion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" value="Exelente">');
                    preguntaOptions.append('<input type="text" class="opcion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" value="Bueno">');
                    preguntaOptions.append('<input type="text" class="opcion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" value="Regular">');
                    preguntaOptions.append('<input type="text" class="opcion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" value="Malo">');
                    preguntaOptions.append('<input type="text" class="opcion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" value="Pésimo">');
                    preguntaForm.find('.texto-pregunta').show();
                    break;
                case 'D':
                    preguntaOptions.append('<input type="text" class="opcion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" placeholder="Respuesta Positiva">');
                    preguntaOptions.append('<input type="text" class="opcion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" placeholder="Respuesta Negativa">');
                    preguntaForm.find('.texto-pregunta').show();
                    break;
                case 'A':
                    preguntaForm.find('.texto-pregunta').show();
                    break;
                default:
                    preguntaForm.find('.texto-pregunta').hide();
                    break;
            }
        });

         $('.pregunta-form').on('click', '.agregar-opcion', function() {
            /*console.log($(this).closest('.pregunta-form > .pregunta-options').children().not('button').length);*/
            contadorOpcion = $(this).closest('.pregunta-form > .pregunta-options').children().not('button').length;
            var preguntaForm = $(this).closest('.pregunta-form');
            var preguntaId = preguntaForm.attr('id');
            $(this).remove();
            preguntaForm.find('.pregunta-options').append('<input type="text" class="opcion form-control mt-2" name="Pregunta[' + preguntaId + '][Opciones][]" placeholder="Opción '+ contadorOpcion +'">');
            preguntaForm.find('.pregunta-options').append('<button type="button" class="agregar-opcion btn btn-success mt-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></button>');
            var preguntaOptions = preguntaForm.find('.pregunta-options');
            preguntaOptions.find('.quitar-opcion').remove();
            var quitarOpcionButton = preguntaOptions.find('.quitar-opcion');
            if (quitarOpcionButton.length === 0) {
                preguntaOptions.append('<button type="button" class="quitar-opcion btn btn-danger mt-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/></svg></button>');
            }
        });

        $('.pregunta-form').on('click', '.quitar-opcion', function() {
            contadorOpcion = $(this).closest('.pregunta-form > .pregunta-options').children().not('button').length;
            var preguntaOptions = $(this).closest('.pregunta-options');
            var opciones = preguntaOptions.find('.opcion');

            if (opciones.length > 2) {
                opciones.last().remove();
                if (opciones.length == 3) $(this).remove();
            } else {
                $(this).remove();
            }
        });

        contadorPregunta =  $('.pregunta-form').length + 1;

    });

    $('#agregar-varias-preguntas').on('click', function() {
        var cantidad = parseInt(prompt("Ingrese la cantidad de preguntas a agregar:", "1"));
        if (!isNaN(cantidad) && cantidad > 0) {
            for (var i = 0; i < cantidad; i++) {
                $('#agregar-pregunta').trigger('click');
            }
        }
    });
});
</script>

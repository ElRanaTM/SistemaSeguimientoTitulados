<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Carrera;

/** @var yii\web\View $this */
/** @var app\models\Area $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\Experiencia $experienciaModel */

?>

<div class="area-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'NombreArea')->textInput(['maxlength' => true])->label('¿En qué area de su trabajo se desempeño?') ?>

    <?= $form->field($model, 'GradoRequerido')->textInput(['maxlength' => true])->label('¿Qué grado fue necesario para acceder a ese puesto?') ?>

    <?= $form->field($model, 'EstadoConocimientos')->dropDownList(['1' => 'Si', '0' => 'No'], ['prompt' => 'Seleccionar'])->label('¿Los conocimientos de la Carrera han sido útiles para el desempeño en éste trabajo?') ?>

    <?= $form->field($model, 'RelacionCarrera')->dropDownList(['1' => 'Si', '0' => 'No'], ['prompt' => 'Seleccionar'])->label('¿El trabajo desempeñado tiene alguna relación con la Carrera que estudió?') ?>

    <?= Yii::$app->user->identity->user_type === 'SuperAdmin' ? 
        $form->field($model, 'idCarrera')->dropDownList(
            Carrera::getCarrerasNombre(), 
            ['prompt' => 'Seleccione Carrera'])->label('Carrera') : 
        $form->field($model, 'idCarrera')->dropDownList(array_combine(
        Yii::$app->user->identity->titulado->getCarreras()->select('id')->column(),
        Yii::$app->user->identity->titulado->getCarreras()->select('NombreCarrera')->column()),
        ['prompt' => 'Seleccionar Carrera'])->label('Carrera'); ?>


    <div class="form-group">
        <?= Html::button('Agregar Comentario', ['class' => 'btn btn-primary add-conocimiento']) ?>
    </div>

    <div id="conocimientos-container">
        <!-- Aquí se agregarán dinámicamente los formularios de Conocimientos -->
    </div>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var container = $("#conocimientos-container");
    var addButton = $(".add-conocimiento");

    $(addButton).click(function() {
        var form = $('<div class="conocimiento-form">' +
                        '<textarea name="Area[Conocimientos][Descripcion][]" placeholder="Escriba un comentario" class="form-control mt-2"></textarea>' +
                        '<button type="button" class="remove-conocimiento btn btn-danger remove-conocimiento mt-2">Quitar Comentario</button>' +
                    '</div>');

        container.append(form);
    });

    $(container).on("click", ".remove-conocimiento", function() {
        $(this).parent(".conocimiento-form").remove();
    });
});
</script>

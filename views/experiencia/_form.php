<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Experiencia;

/** @var yii\web\View $this */
/** @var app\models\Experiencia $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="experiencia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'EstadoActivo')->dropDownList([
        1 => 'Activo',
        0 => 'Inactivo',
    ]) ?>

    <?= $form->field($model, 'Tipo')->dropDownList([
        1 => 'Emprendimiento',
        0 => 'Institución',
    ]) ?>

    <?= $form->field($model, 'Sector')->dropDownList([
        'Industrial/Producción' => 'Industrial/Producción',
        'Comercial' => 'Comercial',
        'Servicios' => 'Servicios',
        'EmpresaPropia' => 'EmpresaPropia',
        'Otros' => 'Otros',
    ], [
        'prompt' => 'Seleccionar Sector...',
    ]) ?>

    <?= $form->field($model, 'TipoSector')->dropDownList([
        'Sector Público' => 'Sector Público',
        'Sector Privado' => 'Sector Privado',
        'ONG' => 'ONG',
    ]) ?>

    <?= $form->field($model, 'EstadoRelacionLaboralCarrera')->dropDownList([
        1 => 'Sí',
        0 => 'No',
    ])->label('¿La carrera que estudiaste te ayudó a desempeñarte en este trabajo?') ?>

    <?= $form->field($model, 'NombreInstitucion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Cargo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RangoSalarial')->dropDownList([
        'A' => 'Menor a Bs. 2362',
        'B' => 'Entre Bs. 2362 - Bs. 2999',
        'C' => 'Entre Bs. 3000 - Bs. 3999',
        'D' => 'Entre Bs. 4000 - Bs. 5999',
        'E' => 'Entre Bs. 6000 - Bs. 7999',
        'F' => 'Superior a Bs. 8000',
    ]) ?>

    <?= $form->field($model, 'PeriodoTiempo')->dropDownList([
        'A' => 'Medio Tiempo',
        'B' => 'Tiempo Completo',
        'C' => 'Otro',
    ]) ?>

    <?= $form->field($model, 'FechaIngreso')->input('date') ?>

    <?= $form->field($model, 'FechaActualizacion')->hiddenInput(['value' => date('Y-m-d H:i:s')])->label(false) ?>


    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Estudios;

/** @var yii\web\View $this */
/** @var app\models\Estudios $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="estudios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'NombreCurso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'GradoAcademico')->dropDownList(
        [
            'Técnico medio' => 'Técnico medio',
            'Técnico superior' => 'Técnico superior',
            'Especialidad' => 'Especialidad',
            'Diplomado' => 'Diplomado',
            'Maestría' => 'Maestría',
            'Doctorado' => 'Doctorado',
            'Post Doctorado' => 'Post Doctorado',
        ],
        ['prompt' => 'Seleccione un Grado']
    ) ?>

    <?php
    $universidades = \Yii::$app->dbAcademica->createCommand("SELECT NombreUniversidad FROM Universidades;")->queryColumn();
    $universidades = array_combine($universidades, $universidades);
    ?>

    <?php //$form->field($model, 'Universidad')->dropDownList($universidades, ['prompt' => 'Seleccione Universidad']) ?>

    <?= $form->field($model, 'Universidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'EstadoActivo')->dropDownList([1 => 'Cursando', 0 => 'Finalizado']) ?>

    <?= $form->field($model, 'FechaActualizacion')->hiddenInput(['value' => date('Y-m-d H:i:s')])->label(false) ?>

    <?= $form->field($model, 'CI')->textInput([
        'maxlength' => true,
        'value' => Yii::$app->user->identity->user_type === 'SuperAdmin' ? null : Yii::$app->user->identity->titulado->CI,
        'readonly' => Yii::$app->user->identity->user_type != 'SuperAdmin',
        //'style' => Yii::$app->user->identity->user_type === 'SuperAdmin' ? '' : '',
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

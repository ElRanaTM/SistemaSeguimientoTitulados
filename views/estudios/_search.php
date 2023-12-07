<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\EstudiosSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="estudios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'EstadoActivo') ?>

    <?= $form->field($model, 'NombreCurso') ?>

    <?= $form->field($model, 'GradoAcademico') ?>

    <?= $form->field($model, 'Universidad') ?>

    <?php // echo $form->field($model, 'FechaActualizacion') ?>

    <?php // echo $form->field($model, 'CI') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

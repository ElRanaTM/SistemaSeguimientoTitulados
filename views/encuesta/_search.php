<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\EncuestaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="encuesta-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'TituloEncuesta') ?>

    <?= $form->field($model, 'Descripcion') ?>

    <?= $form->field($model, 'FechaInicio') ?>

    <?= $form->field($model, 'FechaFin') ?>

    <?php // echo $form->field($model, 'Estado') ?>

    <?php // echo $form->field($model, 'FechaCreacion') ?>

    <?php // echo $form->field($model, 'FechaEdicion') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

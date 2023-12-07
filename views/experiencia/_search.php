<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ExperienciaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="experiencia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'EstadoActivo') ?>

    <?= $form->field($model, 'Tipo') ?>

    <?= $form->field($model, 'Sector') ?>

    <?= $form->field($model, 'TipoSector') ?>

    <?php // echo $form->field($model, 'EstadoRelacionLaboralCarrera') ?>

    <?php // echo $form->field($model, 'NombreInstitucion') ?>

    <?php // echo $form->field($model, 'Cargo') ?>

    <?php // echo $form->field($model, 'RangoSalarial') ?>

    <?php // echo $form->field($model, 'PeriodoTiempo') ?>

    <?php // echo $form->field($model, 'FechaIngreso') ?>

    <?php // echo $form->field($model, 'FechaActualizacion') ?>

    <?php // echo $form->field($model, 'CI') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

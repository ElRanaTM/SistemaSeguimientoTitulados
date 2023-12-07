<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TituladoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="titulado-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'CI') ?>

    <?= $form->field($model, 'Nombres') ?>

    <?= $form->field($model, 'ApPaterno') ?>

    <?= $form->field($model, 'ApMaterno') ?>

    <?= $form->field($model, 'Foto') ?>

    <?php // echo $form->field($model, 'Celular') ?>

    <?php // echo $form->field($model, 'CodPaisCelular') ?>

    <?php // echo $form->field($model, 'PaisActual') ?>

    <?php // echo $form->field($model, 'DepartamentoActual') ?>

    <?php // echo $form->field($model, 'CiudadActual') ?>

    <?php // echo $form->field($model, 'EstadoLaboral') ?>

    <?php // echo $form->field($model, 'EstadoPostGrado') ?>

    <?php // echo $form->field($model, 'FechaActualizacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

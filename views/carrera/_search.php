<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CarreraSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="carrera-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'NombreCarrera') ?>

    <?= $form->field($model, 'FechaIngreso') ?>

    <?= $form->field($model, 'FechaEgreso') ?>

    <?= $form->field($model, 'FechaTitulacion') ?>

    <?php // echo $form->field($model, 'ModalidadDeTitulacion') ?>

    <?php // echo $form->field($model, 'CI') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AreaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="area-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'NombreArea') ?>

    <?= $form->field($model, 'GradoRequerido') ?>

    <?= $form->field($model, 'EstadoConocimientos') ?>

    <?= $form->field($model, 'RelacionCarrera') ?>

    <?php // echo $form->field($model, 'idExperienciaLaboral') ?>

    <?php // echo $form->field($model, 'idCarrera') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

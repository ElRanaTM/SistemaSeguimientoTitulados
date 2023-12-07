<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Carrera $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="carrera-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'NombreCarrera')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'GestionIngreso')->textInput() ?>

    <?= $form->field($model, 'FechaEgreso')->textInput() ?>

    <?= $form->field($model, 'FechaTitulacion')->textInput() ?>

    <?= $form->field($model, 'ModalidadDeTitulacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CI')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

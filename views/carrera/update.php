<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Carrera $model */

$this->title = 'Actualizar Carrera de Titulado: ' . $model->titulado->Nombres . ' ' . $model->titulado->ApPaterno . ' ' . $model->titulado->ApMaterno;
$this->params['breadcrumbs'][] = ['label' => 'Carreras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="carrera-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

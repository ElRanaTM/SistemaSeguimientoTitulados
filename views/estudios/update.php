<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Estudios $model */

$this->title = 'Actualizar Curso: ' . $model->NombreCurso . ' - ' . $model->titulado->Nombres . ' ' . $model->titulado->ApPaterno . ' ' . $model->titulado->ApMaterno;
$this->params['breadcrumbs'][] = ['label' => 'Estudios', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="estudios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

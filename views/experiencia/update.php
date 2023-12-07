<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Experiencia $model */

$this->title = 'Actualizar Experiencia Laboral: ' . $model->titulado->Nombres . ' ' . $model->titulado->ApPaterno . ' ' . $model->titulado->ApMaterno;
$this->params['breadcrumbs'][] = ['label' => 'Experiencias', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="experiencia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

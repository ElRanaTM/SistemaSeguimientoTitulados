<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Encuesta $model */

$this->title = 'Editar Encuesta: ' . $model->TituloEncuesta;
$this->params['breadcrumbs'][] = ['label' => 'Encuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->TituloEncuesta, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar Encuesta';
?>
<div class="encuesta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'preguntasExistente' => $preguntasExistente,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Encuesta $model */

$this->title = 'Agregar Nueva Encuesta';
$this->params['breadcrumbs'][] = ['label' => 'Encuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="encuesta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'preguntasExistente' => [],
    ]) ?>

</div>

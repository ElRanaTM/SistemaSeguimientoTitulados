<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Experiencia $model */

$this->title = 'Agregar Experiencia';
$this->params['breadcrumbs'][] = ['label' => 'Experiencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="experiencia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

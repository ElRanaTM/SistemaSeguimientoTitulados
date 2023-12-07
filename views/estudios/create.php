<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Estudios $model */

$this->title = 'Agregar Curso';
$this->params['breadcrumbs'][] = ['label' => 'Estudios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estudios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

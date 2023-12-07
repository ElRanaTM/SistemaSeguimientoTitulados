<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Carrera $model */

$this->title = 'Agregar Carrera a Titulado';
$this->params['breadcrumbs'][] = ['label' => 'Carreras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="carrera-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

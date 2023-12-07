<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Area $model */

$this->title = 'Editar Opinión';
//$this->params['breadcrumbs'][] = ['label' => 'Opiniones', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => 'Opiniones', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar Opinión';
?>
<div class="area-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

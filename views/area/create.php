<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Area $model */

$this->title = 'Agregar Opinión';
//$this->params['breadcrumbs'][] = ['label' => 'Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

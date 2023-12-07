<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Titulado $model */

$this->title = 'Actualizar Titulado: ' . $model->Nombres . ' '. $model->ApPaterno . ' '. $model->ApMaterno . ' - ' . $model->CI;
$this->params['breadcrumbs'][] = ['label' => 'Titulados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->CI, 'url' => ['view', 'CI' => $model->CI]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="titulado-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

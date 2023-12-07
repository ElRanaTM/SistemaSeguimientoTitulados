<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Titulado $model */

$this->title = 'Agregar Titulado';
$this->params['breadcrumbs'][] = ['label' => 'Titulados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="titulado-create">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) 
    ?>

</div>

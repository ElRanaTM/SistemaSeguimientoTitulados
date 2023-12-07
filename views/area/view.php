<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Area $model */

$this->title = $model->idExperienciaLaboral0->NombreInstitucion . ' - ' . $model->idCarrera0->NombreCarrera;
//$this->params['breadcrumbs'][] = ['label' => 'Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="area-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        
        <?= Yii::$app->user->can('verTodosTitulares') ? Html::a('Borrar Opinión', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'NombreArea',
            'GradoRequerido',
            [
                'attribute' => 'EstadoConocimientos',
                'value' => $model->EstadoConocimientos ? 'Si' : 'No',
            ],
            [
                'attribute' => 'RelacionCarrera',
                'value' => $model->RelacionCarrera ? 'Si' : 'No',
            ],
            //'idExperienciaLaboral',
            //'idCarrera',
        ],
    ]) ?>

    <div class="conocimientos-widget">
        <h2>Opiniones</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Fecha Actualización</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->conocimientos as $conocimiento): ?>
                    <tr>
                        <td><?= nl2br(Html::encode($conocimiento->Descripcion)) ?></td>
                        <td><?= Html::encode(Yii::$app->formatter->asDate($conocimiento->FechaActualizacion, 'php:d/m/Y')) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


</div>

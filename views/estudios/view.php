<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Estudios $model */

$this->title = $model->NombreCurso . ' - ' . $model->GradoAcademico . ' - ' . $model->titulado->Nombres;
$this->params['breadcrumbs'][] = ['label' => 'Estudios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="estudios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= in_array(Yii::$app->user->identity->user_type, array('titulado', 'SuperAdmin')) ? Html::a('Editar Datos del Curso', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : ''?>
        <?= in_array(Yii::$app->user->identity->user_type, array('titulado', 'SuperAdmin')) ? Html::a('Borrar Curso', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro que quieres borrar éste curso?',
                'method' => 'post',
            ],
        ]) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered detail-view table-dark') : array('class' => 'table table-striped table-bordered detail-view'),
        'attributes' => [
            [
                'attribute' => 'EstadoActivo',
                'value' => $model->EstadoActivo ? 'Cursando' : 'Finalizado',
            ],
            'NombreCurso',
            'GradoAcademico',
            'Universidad',
            [
                'attribute' => 'FechaActualizacion',
                'value' => $model->FechaActualizacion ? Yii::$app->formatter->asDate($model->FechaActualizacion, 'full') : null
            ],
            //'CI',
        ],
    ]) ?>

</div>

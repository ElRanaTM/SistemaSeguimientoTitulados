<?php

use app\models\Estudios;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\EstudiosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
Yii::$app->user->can('verTodosTitulares') ? $this->title = 'Estudios Post Grado' : $this->title = 'Mis Estudios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estudios-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= in_array(Yii::$app->user->identity->user_type, array('titulado', 'SuperAdmin')) ? Html::a('Agregar un Curso', ['create'], ['class' => 'btn btn-success']) : ''?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered table-dark') : array('class' => 'table table-striped table-bordered'),
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'CI',
                'visible' => Yii::$app->user->identity->user_type !== 'titulado',
            ],
            'NombreCurso',
            [
                'attribute' => 'GradoAcademico',
                'filter' => [
                    'Técnico medio' => 'Técnico medio',
                    'Técnico superior' => 'Técnico superior',
                    'Especialidad' => 'Especialidad',
                    'Diplomado' => 'Diplomado',
                    'Maestría' => 'Maestría',
                    'Doctorado' => 'Doctorado',
                    'Post Doctorado' => 'Post Doctorado',
                ],
                'visible' => isMobile() ? false : true,
            ],
            [
                'attribute' => 'Universidad',
                'visible' => isMobile() ? false : true,
            ],
            //'FechaActualizacion',
            [
                'attribute' => 'EstadoActivo',
                'value' => function ($model) {
                    return $model->EstadoActivo ? 'Cursando' : 'Finalizado';
                },
                'filter' => [1 => 'Cursando', 0 => 'Finalizado'],
            ],
            in_array(Yii::$app->user->identity->user_type, array('titulado', 'SuperAdmin')) ? [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Estudios $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ]: [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => function ($action, Estudios $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        'pager' => [
            //'pagination' => $pagination,
            'options' => ['class' => 'pagination'],
            'nextPageLabel' => 'Siguiente',
            'prevPageLabel' => 'Anterior',
            'firstPageLabel' => 'Primera',
            'lastPageLabel'  => 'Última',
            'maxButtonCount' => 7,
            'prevPageCssClass' => 'page-item',
            'nextPageCssClass' => 'page-item',
            'linkOptions' => ['class' => 'page-link'],
            'hideOnSinglePage' => true,
        ],
    ]); ?>


</div>

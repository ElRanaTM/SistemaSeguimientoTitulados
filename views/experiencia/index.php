<?php

use app\models\Experiencia;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ExperienciaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

Yii::$app->user->can('verTodosTitulares') ? $this->title = 'Experiencias' : $this->title = 'Mi Experiencia Laboral';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="experiencia-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= in_array(Yii::$app->user->identity->user_type, array('titulado', 'SuperAdmin')) ? Html::a('Añadir Nueva Experiencia Laboral', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered table-dark') : array('class' => 'table table-striped table-bordered'),
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'CI',
                'visible' => Yii::$app->user->identity->user_type !== 'titulado',
            ],
            'NombreInstitucion',
            [
                'attribute' => 'Cargo',
                'visible' => isMobile() ? false : true,
            ],
            //'EstadoActivo',
            [
                'attribute' => 'Tipo',
                'value' => function ($model) {
                    return $model->Tipo ? 'Emprendimiento' : 'Institución';
                },
                'filter' => [1 => 'Emprendimiento', 0 => 'Institución'],
                'visible' => isMobile() ? false : true,
            ],
            [
                'attribute' => 'Sector',
                'filter' => [
                    'Industrial/Producción' => 'Industrial/Producción',
                    'Comercial' => 'Comercial',
                    'Servicios' => 'Servicios',
                    'EmpresaPropia' => 'EmpresaPropia',
                    'Otros' => 'Otros',
                ],
                'visible' => isMobile() ? false : true,
            ],
            [
                'attribute' => 'TipoSector',
                'filter' => [
                    'Sector Público' => 'Sector Público',
                    'Sector Privado' => 'Sector Privado',
                    'ONG' => 'ONG',
                ],
                'visible' => isMobile() ? false : true,
            ],
            //'EstadoRelacionLaboralCarrera',
            //'RangoSalarial',
            //'PeriodoTiempo',
            //'FechaIngreso',
            //'FechaActualizacion',
            in_array(Yii::$app->user->identity->user_type, array('director', 'admin')) ? [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => function ($action, Experiencia $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ]: [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Experiencia $model, $key, $index, $column) {
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

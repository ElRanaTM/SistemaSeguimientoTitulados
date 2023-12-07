<?php

use app\models\Area;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AreaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Areas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->user->can('verTodosTitulares') ? '' : Html::a('Agregar una Opinión', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered table-dark') : array('class' => 'table table-striped table-bordered'),
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'NombreArea',
            'GradoRequerido',
            [
                'attribute' => 'EstadoConocimientos',
                'value' => function ($model) {
                    return $model->EstadoConocimientos ? 'Si' : 'No';
                },
                'filter' => [1 => 'Si', 0 => 'No'],
            ],
            [
                'attribute' => 'RelacionCarrera',
                'value' => function ($model) {
                    return $model->RelacionCarrera ? 'Si' : 'No';
                },
                'filter' => [1 => 'Si', 0 => 'No'],
            ],
            //'idExperienciaLaboral',
            //'idCarrera',
            Yii::$app->user->identity->user_type === 'admin' ? [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Area $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ]: [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => function ($action, Area $model, $key, $index, $column) {
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

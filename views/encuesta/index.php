<?php

use app\models\CarreraUser;
use app\models\Encuesta;
use jino5577\daterangepicker\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\EncuestaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Encuestas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="encuesta-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= in_array(Yii::$app->user->identity->user_type, array('director', 'admin', 'SuperAdmin')) ? Html::a('Crear Encuesta', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered table-dark') : array('class' => 'table table-striped table-bordered'),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'TituloEncuesta',
            //'Descripcion:ntext',
            /*[
                'attribute' => 'FechaInicio',
                'value' => function ($model) {
                    if (extension_loaded('intl')) {
                        return Yii::t('app', '{0, date, MMMM dd, YYYY HH:mm}', [$model->FechaInicio]);
                    } else {
                        return date('d-m-Y G:i:s', $model->FechaInicio);
                    }
                },
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'FechaInicio',
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                        'autoUpdateInput' => false,
                    ]
                ])               
            ],*/
            [
                'attribute' => 'FechaInicio',
                'value' => function ($model) {
                    return $model->FechaInicio ? Yii::$app->formatter->asDate($model->FechaInicio, 'full') : null;
                },
                'filter'=>false,
                'visible' => isMobile() ? false : true,
            ],
            [
                'attribute' => 'FechaFin',
                'value' => function ($model) {
                    return $model->FechaFin ? Yii::$app->formatter->asDate($model->FechaFin, 'full') : null;
                },
                'filter'=>false,
                'visible' => isMobile() ? false : true,
            ],
            [
                'attribute' => 'Estado',
                'value' => function ($model) {
                    return $model->Estado ? 'En curso' : 'Finalizada';
                },
                'filter' => [1 => 'En Curso', 0 => 'Finalizada'],
                'visible' => isMobile() ? false : true,
            ],
            [
                'attribute' => 'Carrera',
                'value' => function ($model) {
                    return CarreraUser::getCarreraSedeNonmbres($model->CodigoCarrera, $model->CodigoSede);
                },
                'visible' => isMobile() ? false : true,
            ],
            //'FechaCreacion',
            //'FechaEdicion',
            //'user_id',
            [
                'class' => ActionColumn::class,
                'visibleButtons' => [
                    'view' => function ($model) {
                        return in_array(Yii::$app->user->identity->user_type, array('director', 'admin', 'SuperAdmin'));
                    },
                    'update' => function ($model) {
                        return in_array(Yii::$app->user->identity->user_type, array('admin', 'SuperAdmin'));
                    },
                    'delete' => function ($model) {
                        return in_array(Yii::$app->user->identity->user_type, array('admin', 'SuperAdmin'));
                    },
                ],
                'template' => '{view} {delete} {responder}',
                'buttons' => [
                    'responder' => function ($url, $model, $key) {
                        if (in_array(Yii::$app->user->identity->user_type, ['titulado', 'SuperAdmin'])) {
                            if ($model->usuarioHaRespondido()) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span> Ver Respuesta',
                                    ['ver-respuesta', 'id' => $model->id, 'ci' => Yii::$app->user->identity->titulado->CI],
                                    ['title' => 'Ver Respuesta']
                                );
                            } else {
                                return $model->Estado ? //nuevo 10 nov
                                Html::a(
                                    '<span class="glyphicon glyphicon-ok"></span> Responder Encuesta',
                                    ['responder-encuesta', 'id' => $model->id],
                                    ['title' => 'Responder Encuesta', 'class' => 'boton-responder-encuesta']
                                )
                                :
                                Html::tag('p', Html::encode('Ya no puede responder'), ['class' => 'encuesta-finished', 'style' => 'color:grey;']); //nuevo 10
                            }
                        }
                        return '';
                    },
                ],
                'urlCreator' => function ($action, Encuesta $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
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

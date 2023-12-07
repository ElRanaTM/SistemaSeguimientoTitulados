<?php

use app\models\Titulado;
use yii\bootstrap4\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TituladoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Titulados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="titulado-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->user->identity->user_type === 'SuperAdmin' ? Html::a('Crear Titulado', ['create'], ['class' => 'btn btn-success']) : ''?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered table-dark') : array('class' => 'table table-striped table-bordered'),
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Foto',
                'format' => 'raw',
                'value' => function ($model) {
                    $imgUrl = Yii::getAlias('@web') . '/images/titulados/' . $model->Foto;
                    $defaultImgUrl = Yii::getAlias('@web') . '/images/titulados/perfil-de-usuario.jpg';
                    if (!empty($model->Foto)) {
                        return Html::img($imgUrl, ['width' => '100px']);
                    } elseif (!empty($model->imageFile)) {
                        return Html::img($model->imageFile, ['width' => '100px']);
                    } else {
                        return Html::img($defaultImgUrl, ['width' => '100px']);
                    }
                },
                'filter' => false,
            ],
            'CI',
            'Nombres',
            'ApPaterno',
            'ApMaterno',/*
            [
                'attribute' => 'Celular',
                'value' => function ($model) {
                    return '+' . $model->CodPaisCelular . ' ' . $model->Celular;
                },
            ],*/
            'PaisActual',
            //'DepartamentoActual',
            //'CiudadActual',
            [
                'attribute' => 'Carreras',
                'value' => function ($model) {
                    return implode(', ', $model->getCarreras()->select('NombreCarrera')->column());
                },
            ],
            [
                'attribute' => 'EstadoLaboral',
                'value' => function ($model) {
                    return $model->EstadoLaboral ? 'Activo' : 'Inactivo';
                },
                'filter' => [1 => 'Activo', 0 => 'Inactivo'],
            ],/*
            [
                'attribute' => 'EstadoPostGrado',
                'value' => function ($model) {
                    return $model->EstadoPostGrado ? 'Cursando' : 'No cursando';
                },
                'filter' => [1 => 'Cursando', 0 => 'No Cursando'],
            ],*/
            //'FechaActualizacion',
            Yii::$app->user->identity->user_type === 'SuperAdmin' ?
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Titulado $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'CI' => $model->CI]);
                 }
            ]
            :
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => function ($action, Titulado $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'CI' => $model->CI]);
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


<?php

use app\models\Titulado;
use app\models\User;
use yii\bootstrap4\LinkPager;
use yii\data\Pagination;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

if (Yii::$app->user->identity->user_type === 'SuperAdmin') {

$this->title = 'Listado de Usuarios';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered table-dark') : array('class' => 'table table-striped table-bordered'),
    'columns' => [
        //'id',
        'name',
        'email',
        [
            'attribute' => 'user_type',
            'value' => function ($model) {
                $role = $model->user_type;
                $class = '';
                if ($role === 'SuperAdmin') {
                    $class = 'super-admin-index-show';
                } elseif ($role === 'admin') {
                    $class = 'admin-index-show';
                } elseif ($role === 'director') {
                    $class = 'director-index-show';
                }
        
                return Html::tag('span', Html::encode($role), ['class' => $class]);
            },
            'format' => 'raw',
        ],  /*     
        [
            'class' => ActionColumn::class,
            
            'urlCreator' => function ($action, User $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->id]);
            },
            'visible' => function ($model) {
                return $model->name == '10020449LP';
            },
        ],*/
    ],
]);
}
else if (in_array(Yii::$app->user->identity->user_type, array('director', 'admin'))) {
$this->title = 'Seguimiento a titulados';
?>
<div class="site-index">


    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Sistema de seguimiento a Titulados</h1>
        <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th>Titulado</th>
                    <th>Carreras</th>
                    <th>Estado Laboral</th>
                    <th>Contacto</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = Titulado::find();
                    $pagination = new Pagination([
                        'defaultPageSize' => 10, // Número de elementos por página
                        'totalCount' => $query->count(),
                    ]);
                    $titulados = $query->offset($pagination->offset)
                            ->limit($pagination->limit)
                            ->all();
                    foreach ($titulados as $titulado): ?>
                    <tr>
                        <td><?= Html::encode($titulado->Nombres . ' ' . $titulado->ApPaterno . ' ' . $titulado->ApMaterno) ?></td>
                        <td>
                            <?php foreach ($titulado->carreras as $carrera): ?>
                                <?= Html::encode($carrera->NombreCarrera) ?><br>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <span class="estado-laboral <?= $titulado->EstadoLaboral ? 'verde' : 'rojo' ?>">
                                <?= $titulado->EstadoLaboral ? 'Trabajando' : 'Sin Trabajo' ?>
                            </span>
                        </td>
                        <td>
                            <?= Html::encode('+' . $titulado->CodPaisCelular . ' ') ?>
                            
                            <?= Html::encode($titulado->Celular) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination-wrapper">
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'options' => ['class' => 'pagination'],
            'nextPageLabel' => 'Siguiente',
            'prevPageLabel' => 'Anterior',
            'maxButtonCount' => 5,
            'prevPageCssClass' => 'page-item',
            'nextPageCssClass' => 'page-item',
            'linkOptions' => ['class' => 'page-link'],
            'hideOnSinglePage' => true,
        ]) ?>
        </div>
    </div>
</div>
<?php } else {
    echo "<p>No puede acceder a esta página.</p>";
}?>
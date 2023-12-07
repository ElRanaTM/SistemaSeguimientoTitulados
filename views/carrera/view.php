<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Carrera $model */

$this->title = $model->titulado->Nombres . ' ' . $model->titulado->ApPaterno . ' ' . $model->titulado->ApMaterno;
$this->params['breadcrumbs'][] = ['label' => 'Carreras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="carrera-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Borrar Carrera de Titulado', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered detail-view table-dark') : array('class' => 'table table-striped table-bordered detail-view'),
        'attributes' => [
            'id',
            'NombreCarrera',
            'GestionIngreso',
            'FechaEgreso',
            'FechaTitulacion',
            'ModalidadDeTitulacion',
            'CI',
        ],
    ]) ?>

</div>

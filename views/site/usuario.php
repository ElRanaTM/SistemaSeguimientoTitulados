<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Mi Usuario';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-profile">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cambiar Correo Electrónico', ['cambiar-email'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Cambiar Contraseña', ['cambiar-password'], ['class' => 'btn btn-warning']) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'options' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered detail-view table-dark') : array('class' => 'table table-striped table-bordered detail-view'),
        'attributes' => [
            'name',
            'email'
        ],
    ]) ?>

</div>

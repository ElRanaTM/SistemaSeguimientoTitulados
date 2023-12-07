<?php

use app\models\Conocimientos;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Experiencia $model */

$this->title = $model->NombreInstitucion . ' - ' . $model->titulado->Nombres;
$this->params['breadcrumbs'][] = ['label' => 'Experiencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="experiencia-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= in_array(Yii::$app->user->identity->user_type, array('director', 'admin')) ? '' : Html::a('Editar Datos de la Experiencia', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= in_array(Yii::$app->user->identity->user_type, array('director', 'admin')) ? '' : Html::a('Borrar Experiencia Laboral', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro que quieres borrar ésta experiencia laboral?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered detail-view table-dark') : array('class' => 'table table-striped table-bordered detail-view'),
        'attributes' => [
            'EstadoActivo' => [
                'attribute' => 'Estado',
                'value' => $model->EstadoActivo ? 'En actividad' : 'Inactivo',
            ],
            [
                'attribute' => 'Tipo',
                'value' => $model->Tipo ? 'Emprendimiento' : 'Institución',
            ],
            'Sector',
            'TipoSector',
            [
                'attribute' => 'EstadoRelacionLaboralCarrera',
                'value' => $model->EstadoRelacionLaboralCarrera ? 'Si' : 'No',
            ],
            'NombreInstitucion',
            'Cargo',
            [
                'attribute' => 'RangoSalarial',
                'value' => [
                    'A' => 'Menor a Bs. 2362',
                    'B' => 'Entre Bs. 2362 - Bs. 2999',
                    'C' => 'Entre Bs. 3000 - Bs. 3999',
                    'D' => 'Entre Bs. 4000 - Bs. 5999',
                    'E' => 'Entre Bs. 6000 - Bs. 7999',
                    'F' => 'Superiror a Bs. 8000',
                ][$model->RangoSalarial],
            ],
            [
                'attribute' => 'PeriodoTiempo',
                'value' => [
                    'A' => 'Medio Tiempo',
                    'B' => 'Tiempo Completo',
                    'C' => 'Otro',
                ][$model->PeriodoTiempo],
            ],
            [
                'attribute' => 'FechaIngreso',
                'value' => Yii::$app->formatter->asDate($model->FechaIngreso, 'full'),
            ],
            [
                'attribute' => 'FechaActualizacion',
                'value' => Yii::$app->formatter->asDate($model->FechaActualizacion, 'full'),
            ],
        ],
    ]) ?>


    <?= in_array(Yii::$app->user->identity->user_type, array('director', 'admin')) ? '' : ($model->areas ? '' :  Html::a('Agregar opinión', ['area/create', 'idExperienciaLaboral' => $model->id], ['class' => 'btn btn-success'])) ?>

    <?php foreach ($model->areas as $index => $area): ?>
        <div class="area-container">
        <h2>Área de Opinión</h2>
        <?= in_array(Yii::$app->user->identity->user_type, array('titulado', 'SuperAdmin')) ? Html::a('Editar opinión', ['area/update', 'id' => $area->id, 'idExperienciaLaboral' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
            <?= DetailView::widget([
                'model' => $area,
                'options' => Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? array('class' => 'table table-striped table-bordered detail-view table-dark') : array('class' => 'table table-striped table-bordered detail-view'),
                'attributes' => [
                    'NombreArea',
                    'GradoRequerido',
                    [
                        'attribute' => 'EstadoConocimientos',
                        'value' => $area->EstadoConocimientos ? 'Si' : 'No',
                    ],
                    [
                        'attribute' => 'RelacionCarrera',
                        'value' => $area->RelacionCarrera ? 'Si' : 'No',
                    ],
                ],
            ]) ?>

            <div class="conocimientos-widget">
                <h3>Comentarios</h3>

                <table class="<?= Yii::$app->request->cookies->getValue('theme', 'light') === 'dark' ? 'table table-striped table-bordered detail-view table-dark' : 'table table-striped table-bordered detail-view' ?>">
                    <tbody>
                        <?php if ($area->conocimientos == null){ echo "<p>No hay comentarios</p>"; } foreach ($area->conocimientos as $index => $conocimiento): ?>
                            <tr>
                                <td><?= nl2br(Html::encode($conocimiento->Descripcion)) ?></td>
                                <?= isMobile() ? '' : '<td>' . Yii::$app->formatter->asDate($conocimiento->FechaActualizacion, 'full') . '</td>' ?>
                                <td>
                                    <?= in_array(Yii::$app->user->identity->user_type, array('director', 'admin')) ? '' : Html::button('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/></svg>', [
                                        'class' => 'btn btn-danger delete-comment',
                                        'data' => [
                                            'area-id' => $area->id,
                                            'comment-index' => $index,
                                        ],
                                    ]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?= in_array(Yii::$app->user->identity->user_type, array('director', 'admin')) ? '' : Html::button('Agregar Comentario', ['class' => 'btn btn-primary', 'id' => 'open-comment-modal']) ?>
            <div class="modal fade" id="comment-modal" tabindex="-1" aria-labelledby="comment-modal-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="comment-modal-label">Agregar Comentario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php
                            $form = ActiveForm::begin(['id' => 'comment-form']);
                            $area->conocimientos ? $conocimiento->Descripcion = '' : $conocimiento = new Conocimientos();
                            ?>
                            <?= $form->field($conocimiento, 'Descripcion')->textarea(['id' => 'comentario-descripcion', 'placeholder' => 'Escriba un comentario', 'class' => 'form-control'])->label(false) ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="save-comment">Guardar opinión</button>
                        </div>
                    </div>
                </div>
            </div>
                <?php if ($index < count($model->areas) - 1): ?>
                <hr class="area-divider">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#open-comment-modal").click(function() {
        $("#comment-modal").modal("show");
    });

    $("#save-comment").click(function() {
        var comentario = $("#comentario-descripcion").val().trim();
        if (comentario === '') {
            alert('El campo de comentario no puede estar vacío.');
        } else {
            var formData = $("#comment-form").serialize();
            var areaId = <?= $model->areas ? $area->id : '' ?>;
            $.ajax({
                type: "POST",
                url: "guardar-comentario",
                data: formData + "&areaId=" + areaId,
                success: function(response) {
                    $("#comment-modal").modal("hide");
                    if(response.success == true){ 
                        location.reload();
                    }
                }
            });
        }
    });

    $(".delete-comment").click(function() {
        var areaId = $(this).data("area-id");
        var commentIndex = $(this).data("comment-index");
        $.ajax({
            type: "POST",
            url: "eliminar-comentario",
            data: {
                areaId: areaId,
                commentIndex: commentIndex,
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert("No se pudo eliminar el comentario.");
                }
            }
        });
    });
});
</script>

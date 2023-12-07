<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Country;

/** @var yii\web\View $this */
/** @var app\models\Titulado $model */
/** @var yii\widgets\ActiveForm $form */

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="titulado-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'CI')->textInput(['maxlength' => true, 'readonly' => true]) ?>
    <?= $form->field($model, 'Nombres')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'ApPaterno')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'ApMaterno')->textInput(['maxlength' => true]) ?>

    <?php
    if (!empty($model->Foto)) {
        echo Html::img(Yii::getAlias('@web') . '/images/titulados/' . $model->Foto, ['id' => 'image-preview', 'width' => '100px']);
    } else {
        echo Html::img('', ['id' => 'image-preview', 'width' => '100px', 'style' => 'display:none;']);
    }
    ?>

    <?= $form->field($model, 'imageFile')->fileInput(['id' => 'image-input', 'accept' => '.png, .jpg, .jpeg']) ?>
    
    <div class="form-row">
        <div class="col-auto">
            
        <?= $form->field($model, 'CodPaisCelular')->dropDownList(
            Country::getCallingCodes(),
            [
                'options' => [591 => ['selected' => true]],
            ]
        ) ?>

        </div>
        <div class="col">
            <?= $form->field($model, 'Celular')->textInput(['type' => 'number']) ?>
        </div>
    </div>

    <?= $form->field($model, 'PaisActual')->dropDownList(
        Country::getCountryNames(), 
        ['prompt' => 'Seleccione un país', 'options' => ['Bolivia' => ['selected' => true]]]
    ) ?>

    <?= $form->field($model, 'DepartamentoActual')->dropDownList(
        [
            'Beni' => 'Beni',
            'Chuquisaca' => 'Chuquisaca',
            'Cochabamba' => 'Cochabamba',
            'La Paz' => 'La Paz',
            'Oruro' => 'Oruro',
            'Pando' => 'Pando',
            'Potosí' => 'Potosí',
            'Santa Cruz' => 'Santa Cruz',
            'Tarija' => 'Tarija',
        ],
        ['prompt' => 'Seleccione un departamento']
    ) ?>
    <?= $form->field($model, 'CiudadActual')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'EstadoLaboral')->textInput(['type' => 'number'])->dropDownList([0 => 'Inactivo', 1 => 'Activo']) ?>
    <?= $form->field($model, 'EstadoPostGrado')->textInput(['type' => 'number'])->dropDownList([0 => 'No Cursando', 1 => 'Cursando']) ?>
    <?= $form->field($model, 'FechaActualizacion')->hiddenInput(['value' => date('Y-m-d H:i:s')])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    const getFlagEmoji = countryCode=>String.fromCodePoint(...[...countryCode.toUpperCase()].map(x=>0x1f1a5+x.charCodeAt()));

    function getElementsByValue(value, tag, node) {
        var values = new Array();
        if (tag == null)
            tag = "*";
        if (node == null)
            node = document;
        var search = node.getElementsByTagName(tag);
        var pat = new RegExp(value, "i");
        for (var i=0; i<search.length; i++) {
            if (pat.test(search[i].value))
                values.push(search[i]);
        }
        return values;
    }
</script>

<?php $cod_pais = Country::getCountryCodes();
foreach( $cod_pais as $index => $codp ) {
    ?>
    <script>
        /*getElementsByValue("pais-<?= $index ?>","option")[0].innerHTML += getFlagEmoji("<?= $codp ?>");*/
        /*getElementsByValue(<?= $index ?>,"option", document.getElementById("titulado-codpaiscelular"))[0].innerHTML += getFlagEmoji("<?= $codp ?>");*/
        /*console.log(getElementsByValue(<?= $index ?>,"option", document.getElementById("titulado-codpaiscelular")));*/
    </script>
<?php
}?>

<script>
$(document).ready(function() {
    $('#image-input').on('change', function(e) {
        var input = e.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            $('#image-preview').hide();
        }
    });
});
</script>

<?php
$this->registerJs('
    var paisActualField = $("#' . Html::getInputId($model, 'PaisActual') . '");
    var departamentoContainer = $("#departamento-container");
    var departamentoField = $("#' . Html::getInputId($model, 'DepartamentoActual') . '");
    var ciudadField = $("#' . Html::getInputId($model, 'CiudadActual') . '");
    toggleDepartamentoFields();

    paisActualField.on("change", function() {
        toggleDepartamentoFields();
    });
    function toggleDepartamentoFields() {
        if (paisActualField.val() === "Bolivia") {
            departamentoContainer.show();
            departamentoField.prop("disabled", false);
            ciudadField.prop("disabled", false);
        } else {
            departamentoContainer.hide();
            departamentoField.prop("disabled", true);
            ciudadField.prop("disabled", true);
        }
    }
');
?>

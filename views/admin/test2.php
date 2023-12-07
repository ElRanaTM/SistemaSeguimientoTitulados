<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Respuestas</title>
    <!-- Agrega el enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>const getFlagEmoji = countryCode=>String.fromCodePoint(...[...countryCode.toUpperCase()].map(x=>0x1f1a5+x.charCodeAt()));</script>
    <?php

use app\models\Country;

$cod_call = Country::getCallingCodes();


echo '<select>';
foreach( $cod_call as $cod ) {
    echo '<option id="demo-'. $cod .'" value="' . $cod . '">' . '+' . $cod . '</option>';
 }
 echo '</select>';

$a = Country::findAlphaByCalling(591); {
    echo strtoupper($a) . '<br>';
    
 } 
 
 ?>

<?php $cod_pais = Country::getCountryCodes();
print_r($cod_pais);
foreach( $cod_pais as $index => $codp ) {
    ?>
    <script>document.getElementById("demo-<?= $index ?>").innerHTML += getFlagEmoji("<?= $codp ?>");</script>
<?php
 }?>

 
    <div class="container">
        <h1>Cuestionario</h1>
        <?= var_dump(1 == '1') ?>
        <?= var_dump(1 === '1') ?>
        <form id="cuestionarioForm">
            <!-- Aquí generaremos dinámicamente los campos de acuerdo al TipoPregunta -->
        </form>
    </div>
    <script>
        

        

                // Datos simulados para el cuestionario
        var preguntas = [
            {
                TipoPregunta: 'U',
                TextoPregunta: 'Pregunta de opción múltiple - Respuesta única',
                Opciones: ['Opción 1', 'Opción 2', 'Opción 3']
            },
            {
                TipoPregunta: 'M',
                TextoPregunta: 'Pregunta de opción múltiple - Respuesta múltiple',
                Opciones: ['Opción A', 'Opción B', 'Opción C']
            },
            {
                TipoPregunta: 'P',
                TextoPregunta: 'Pregunta de escala de puntuación',
                Opciones: { desde: 1, hasta: 5, intervalo: 1 }
            },
            {
                TipoPregunta: 'L',
                TextoPregunta: 'Pregunta de escala de Likert',
                Opciones: ['Totalmente en desacuerdo', 'En desacuerdo', 'Ni de acuerdo ni en desacuerdo', 'De acuerdo', 'Totalmente de acuerdo']
            },
            {
                TipoPregunta: 'S',
                TextoPregunta: 'Pregunta de escala semántica diferencial',
                Opciones: ['Opción A', 'Opción B', 'Opción C', 'Opción D', 'Opción E']
            },
            {
                TipoPregunta: 'D',
                TextoPregunta: 'Pregunta dicotómica',
                Opciones: ['Sí', 'No']
            },
            {
                TipoPregunta: 'A',
                TextoPregunta: 'Pregunta abierta'
            }
        ];

        var form = document.getElementById('cuestionarioForm');

        preguntas.forEach(function(pregunta, index) {
            var preguntaContainer = document.createElement('div');
            preguntaContainer.className = 'mb-3';

            var preguntaLabel = document.createElement('label');
            preguntaLabel.textContent = pregunta.TextoPregunta;
            preguntaContainer.appendChild(preguntaLabel);

            switch (pregunta.TipoPregunta) {
                case 'U':
                    pregunta.Opciones.forEach(function(opcion, optIndex) {
                        var radioInput = document.createElement('input');
                        radioInput.type = 'radio';
                        radioInput.name = 'pregunta_' + index;
                        radioInput.value = opcion;
                        
                        var opcionLabel = document.createElement('label');
                        opcionLabel.textContent = opcion;

                        preguntaContainer.appendChild(radioInput);
                        preguntaContainer.appendChild(opcionLabel);
                    });
                    break;
                case 'M':
                    pregunta.Opciones.forEach(function(opcion, optIndex) {
                        var checkboxInput = document.createElement('input');
                        checkboxInput.type = 'checkbox';
                        checkboxInput.name = 'pregunta_' + index + '[]';
                        checkboxInput.value = opcion;
                        
                        var opcionLabel = document.createElement('label');
                        opcionLabel.textContent = opcion;

                        preguntaContainer.appendChild(checkboxInput);
                        preguntaContainer.appendChild(opcionLabel);
                    });
                    break;
                case 'P':
                    var selectInput = document.createElement('select');
                    selectInput.name = 'pregunta_' + index;
                    for (var i = pregunta.Opciones.desde; i <= pregunta.Opciones.hasta; i += pregunta.Opciones.intervalo) {
                        var option = document.createElement('option');
                        option.value = i;
                        option.text = i;
                        selectInput.appendChild(option);
                    }
                    preguntaContainer.appendChild(selectInput);
                    break;
                case 'L':
                    var selectInput = document.createElement('select');
                    selectInput.name = 'pregunta_' + index;
                    for (var i = 0; i < pregunta.Opciones.length - 1; i++) {
                        var option = document.createElement('option');
                        option.value = pregunta.Opciones[i];
                        option.text = pregunta.Opciones[i];
                        selectInput.appendChild(option);
                    }
                    preguntaContainer.appendChild(selectInput);
                    break;
                case 'S':
                    pregunta.Opciones.forEach(function(opcion, optIndex) {
                        var radioInput = document.createElement('input');
                        radioInput.type = 'radio';
                        radioInput.name = 'pregunta_' + index;
                        radioInput.value = opcion;
                        
                        var opcionLabel = document.createElement('label');
                        opcionLabel.textContent = opcion;

                        preguntaContainer.appendChild(radioInput);
                        preguntaContainer.appendChild(opcionLabel);
                    });
                    break;
                case 'D':
                    // Usamos botones de radio para preguntas dicotómicas
                    pregunta.Opciones.forEach(function(opcion, optIndex) {
                        var radioInput = document.createElement('input');
                        radioInput.type = 'radio';
                        radioInput.name = 'pregunta_' + index;
                        radioInput.value = opcion;
                        // Estilos de Bootstrap para los botones de radio
                        radioInput.className = 'btn-check';
                        
                        var opcionLabel = document.createElement('label');
                        opcionLabel.textContent = opcion;
                        opcionLabel.className = 'btn btn-secondary';
                        
                        preguntaContainer.appendChild(radioInput);
                        preguntaContainer.appendChild(opcionLabel);
                    });
                    break;
                case 'A':
                    var textInput = document.createElement('textarea');
                    textInput.name = 'pregunta_' + index;
                    textInput.rows = 3;
                    preguntaContainer.appendChild(textInput);
                    break;
            }

            form.appendChild(preguntaContainer);
        });
        let div = document.createElement("div");
        document.getElementById('cuestionarioForm').append(div);
        div.append(getFlagEmoji('GB')) // 🇬🇧
        div.append(getFlagEmoji('JP')) // 🇯🇵
        div.append(getFlagEmoji('ZA')) // 🇿🇦
        div.append(getFlagEmoji('bo'))
    </script>

    <?= print_r(\Faker\Factory::create('es_ES')->text()) ?>

    <br>
    <div>
        <p>
            <?php echo __FILE__; ?>
        </p>
        <br>
        <p>
            <?php echo Yii::$app->urlManager->createAbsoluteUrl('/images/usfx-logo.ico'); ?>
        </p>
        <br>
        <img src="<?=Yii::$app->urlManager->createAbsoluteUrl('/images/titulados/perfil-de-usuario.jpg')?>" alt="">
        <p>
            <?php echo realpath(dirname(__FILE__).'/../../') . '\web\images\titulados'; ?>
        </p>
        <br>
        <p>
            <?php echo \Yii::getAlias('@app') . '/web/images/titulados/'; ?>
        </p>
    </div>
    

<div class="arrow bounce">
    <svg xmlns="http://www.w3.org/2000/svg" height="5em" viewBox="0 0 384 512"><path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"/></svg>
</div>

<div>
    
</div>



</body>
</html>

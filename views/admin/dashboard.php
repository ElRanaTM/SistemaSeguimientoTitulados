<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>   
</head>
<body>

<?php

use app\models\CarreraUser;
use yii\helpers\Html;
if (in_array(Yii::$app->user->identity->user_type, array('director', 'admin', 'SuperAdmin'))) {
    ?>

    <div class="admin-dashboard">
        <h2>Resumen de Estadísticas</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios Registrados</h5>
                        <p class="card-text"><?= Yii::$app->session->get('codigoCarrera') != null ?
                        app\models\User::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->count()
                        : app\models\User::find()->where(['<>', 'user_type', 'admin'])->andWhere(['<>', 'user_type', 'director'])->andWhere(['<>', 'user_type', 'SuperAdmin'])->count(); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Titulados Registrados</h5>
                        <p class="card-text"><?= Yii::$app->session->get('codigoCarrera') != null ?
                        app\models\Titulado::find()->joinWith('carreras')
                        ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])
                        ->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->count()
                        : app\models\Titulado::find()->count() ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Titulados Cursando un Post Grado</h5>
                        <p class="card-text"><?= Yii::$app->session->get('codigoCarrera') != null ? 
                        app\models\Estudios::find()->joinWith(['titulado', 'titulado.carreras'])
                        ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                        ->andWhere(['estudios.EstadoActivo' => true])->count()
                        : app\models\Estudios::find()->where(['EstadoActivo' => true])->count(); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Titulados Trabajando</h5>
                        <p class="card-text"><?= Yii::$app->session->get('codigoCarrera') != null ?
                        app\models\Titulado::find()->joinWith('carreras')
                        ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                        ->andWhere(['EstadoLaboral' => true])->count() 
                        : app\models\Titulado::find()->where(['EstadoLaboral' => true])->count(); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Titulados Sin Trabajo</h5>
                        <p class="card-text"><?= Yii::$app->session->get('codigoCarrera') != null ?
                        app\models\Titulado::find()->joinWith('carreras')
                        ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                        ->andWhere(['EstadoLaboral' => false])->count() 
                        : app\models\Titulado::find()->where(['EstadoLaboral' => false])->count(); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Titulados sin Experiencia</h5>
                        <p class="card-text"><?= Yii::$app->session->get('codigoCarrera') != null ?
                        app\models\Titulado::find()->leftJoin('experiencia', 'titulado.CI = experiencia.CI')
                        ->joinWith('carreras')->where(['experiencia.id' => null])
                        ->andWhere(['Carrera.CodigoCarrera' => Yii::$app->session->get('codigoCarrera')])->count() 
                        : app\models\Titulado::find()->leftJoin('experiencia', 'titulado.CI = experiencia.CI')
                        ->where(['experiencia.id' => null])->count(); ?></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="admin-dashboard">
        <h2>Estadísticas de Retroalimentación</h2>

        <div class="row">
            <div class="col-md-6">
                <h5 class="card-title">Gestión de Titulación</h5>
                <canvas id="gestionTitulacionChart" width="400" height="300"></canvas>
            </div>
            <div class="col-md-6">
                <h5 class="card-title">Tipo de Negocio</h5>
                <canvas id="tipoNegocioChart" width="400" height="300"></canvas>
            </div>
            <div class="col-md-6">
                <h5 class="card-title">Sector Productivo</h5>
                <canvas id="sectorChart" width="400" height="300"></canvas>
            </div>
            <div class="col-md-6">
                <h5 class="card-title">Sector Económico</h5>
                <canvas id="tipoSectorChart" width="400" height="300"></canvas>
            </div>
            <div class="col-md-6">
                <h5 class="card-title">¿Tiene relación la carrera estudiada con el trabajo?</h5>
                <canvas id="EstadoRelacionLaboralCarreraChart" width="400" height="300"></canvas>
            </div>
            <div class="col-md-6">
                <h5 class="card-title">Cargos ocupados</h5>
                <canvas id="cargoChart" width="400" height="300"></canvas>
            </div>
            <div class="col-md-6">
                <h5 class="card-title">Rango Salarial</h5>
                <canvas id="RangoSalarialChart" width="400" height="300"></canvas>
            </div>
            <div class="col-md-6">
                <h5 class="card-title">Periodo de Tiempo</h5>
                <canvas id="PeriodoTiempoChart" width="400" height="300"></canvas>
            </div>
            <!-- mas graficos aqui -->
        </div>

        <!-- mas cosas -->
        <?= Html::a('Generar informe', ['informe/generar-informe-pdf'], ['class' => 'btn btn-success']) ?>

        <button id="exportButton" class="btn btn-success">Descargar Gráficas</button>
    </div>


<?php
} else {
    echo "No puede acceder a esta página.";
}
?>
</body>

<script>
    <?php

    $titulacionYears = Yii::$app->session->get('codigoCarrera') != null ? 
    app\models\Carrera::find()->select(['YEAR(FechaTitulacion) as TitulacionYear'])->distinct()->orderBy(['TitulacionYear' => SORT_ASC])->where(['CodigoCarrera' => Yii::$app->session->get('codigoCarrera')])->column()
    : app\models\Carrera::find()->select(['YEAR(FechaTitulacion) as TitulacionYear'])->distinct()->orderBy(['TitulacionYear' => SORT_ASC])->column();

    $gestionTitulacionLabels = [];
    $gestionTitulacionData = [];

    foreach ($titulacionYears as $year) {

        $tituladosAntes = Yii::$app->session->get('codigoCarrera') == null ? 
        app\models\Carrera::find()->where(['<', 'FechaTitulacion', $year . '-07-01'])->andWhere(['>', 'FechaTitulacion', $year . '-01-01'])->count()
        : app\models\Carrera::find()->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['<', 'FechaTitulacion', $year . '-07-01'])->andWhere(['>', 'FechaTitulacion', $year . '-01-01'])->count();
        $tituladosDespues = Yii::$app->session->get('codigoCarrera') != null ? 
        app\models\Carrera::find()->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['>=', 'FechaTitulacion', $year . '-07-01'])->andWhere(['<', 'FechaTitulacion', $year . '-12-31'])->count()
        : app\models\Carrera::find()->where(['>=', 'FechaTitulacion', $year . '-07-01'])->andWhere(['<', 'FechaTitulacion', $year . '-12-31'])->count();
        
        $gestionTitulacionLabels[] = '1/' . $year;
        $gestionTitulacionLabels[] = '2/' . $year;
        
        $gestionTitulacionData[] = $tituladosAntes;
        $gestionTitulacionData[] = $tituladosDespues;
    }
    ?>
    var gestionTitulacionData = {
    labels: <?= json_encode($gestionTitulacionLabels) ?>,
    datasets: [{
        label: 'Gestión de Titulación',
        data: <?= json_encode($gestionTitulacionData) ?>,
        backgroundColor: ['#36A2EB', '#FF6384']
        }]
    };

    var tipoNegocioData = {
        labels: ["Emprendimiento", "Institución"],
        datasets: [{
            label: 'Tipo de Negocio',
            data: [
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['Tipo' => 1])->count() 
                : app\models\Experiencia::find()->where(['Tipo' => 1])->count() ?>,
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['Tipo' => 0])->count() 
                : app\models\Experiencia::find()->where(['Tipo' => 0])->count() ?>
            ],
            backgroundColor: ['#FFCE56', '#4BC0C0']
        }]
    };
    <?php
        $sectores = app\models\Experiencia::find()->select(['Sector'])->distinct()->column();
    ?>
    var sectorData = {
        labels: <?= json_encode($sectores) ?>,
        datasets: [{
            label: 'Sector Productivo',
            data: [
                <?= implode(',', array_map(function ($sector) {
                    return  Yii::$app->session->get('codigoCarrera') != null ? 
                    app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['Sector' => $sector])->count()
                    : app\models\Experiencia::find()->where(['Sector' => $sector])->count();
                }, $sectores)) ?>
            ],
            backgroundColor: ['#FFCE56', '#4BC0C0', '#FF6384', '#FF6749', '#EBC0A9']
        }]
    };

    <?php
        $tipoSectores = app\models\Experiencia::find()->select(['TipoSector'])->distinct()->column();
    ?>
    var tipoSectorData = {
        labels: <?= json_encode($tipoSectores) ?>,
        datasets: [{
            label: 'Sector Económico',
            data: [
                <?= implode(',', array_map(function ($tipoSector) {
                    return Yii::$app->session->get('codigoCarrera') != null ? 
                    app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['TipoSector' => $tipoSector])->count()
                    : app\models\Experiencia::find()->where(['TipoSector' => $tipoSector])->count();
                }, $tipoSectores)) ?>
            ],
            backgroundColor: ['#FFCE56', '#4BC0C0', '#FF6384', '#FF6749']
        }]
    };

    var EstadoRelacionLaboralCarreraData = {
        labels: ["Si", "No"],
        datasets: [{
            label: 'Relación con la carrera estudiada',
            data: [
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['EstadoRelacionLaboralCarrera' => 1])->count()
                : app\models\Experiencia::find()->where(['EstadoRelacionLaboralCarrera' => 1])->count() ?>,
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['EstadoRelacionLaboralCarrera' => 0])->count()
                : app\models\Experiencia::find()->where(['EstadoRelacionLaboralCarrera' => 0])->count() ?>
            ],
            backgroundColor: ['#FFCE56', '#4BC0C0']
        }]
    };

    <?php
        $cargos = Yii::$app->session->get('codigoCarrera') != null ? 
        app\models\Experiencia::find()->select(['Cargo'])->distinct()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->column()
        : app\models\Experiencia::find()->select(['Cargo'])->distinct()->column();
    ?>
    var cargoData = {
        labels: <?= json_encode($cargos) ?>,
        datasets: [{
            label: 'Cargo',
            data: [
                <?= implode(',', array_map(function ($cargo) {
                    return Yii::$app->session->get('codigoCarrera') != null ? 
                    app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['Cargo' => $cargo])->count()
                    : app\models\Experiencia::find()->where(['Cargo' => $cargo])->count();
                }, $cargos)) ?>
            ],
            backgroundColor: ['#FFCE56', '#4BC0C0', '#FF6384', '#FF6749', '#EBC0A9']
        }]
    };

    var RangoSalarialData = {
        labels: ["Menos de Bs. 2362", "Entre Bs. 2362 - Bs. 2999", "Entre Bs. 3000 - Bs. 3999", "Entre Bs. 4000 - Bs. 5999", "Entre Bs. 6000 - Bs. 7999", "Superior a Bs. 8000"],
        datasets: [{
            label: 'Rango Salarial',
            data: [
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'A'])->count()
                : app\models\Experiencia::find()->where(['RangoSalarial' => 'A'])->count() ?>,
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'B'])->count()
                : app\models\Experiencia::find()->where(['RangoSalarial' => 'B'])->count() ?>,
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'C'])->count()
                : app\models\Experiencia::find()->where(['RangoSalarial' => 'C'])->count() ?>,
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'D'])->count()
                : app\models\Experiencia::find()->where(['RangoSalarial' => 'D'])->count() ?>,
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'E'])->count()
                : app\models\Experiencia::find()->where(['RangoSalarial' => 'E'])->count() ?>,
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'F'])->count()
                : app\models\Experiencia::find()->where(['RangoSalarial' => 'F'])->count() ?>
            ],
            backgroundColor: ['#FFCE56', '#4BC0C0', '#FF6384', '#FF6749', '#EBC0A9']
        }]
    };

    var PeriodoTiempoData = {
        labels: ["Medio Tiempo", "Tiempo Completo", "Otro"],
        datasets: [{
            label: 'Periodo de Tiempo',
            data: [
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['PeriodoTiempo' => 'A'])->count()
                : app\models\Experiencia::find()->where(['PeriodoTiempo' => 'A'])->count() ?>,
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['PeriodoTiempo' => 'B'])->count()
                : app\models\Experiencia::find()->where(['PeriodoTiempo' => 'B'])->count() ?>,
                <?= Yii::$app->session->get('codigoCarrera') != null ? 
                app\models\Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['PeriodoTiempo' => 'C'])->count()
                : app\models\Experiencia::find()->where(['PeriodoTiempo' => 'C'])->count() ?>
            ],
            backgroundColor: ['#FF6384', '#FF6749', '#EBC0A9']
        }]
    };

    // crear graficos
    var gestionTitulacionChart = new Chart(document.getElementById('gestionTitulacionChart'), {
        type: 'bar',
        data: gestionTitulacionData
    });

    var tipoNegocioChart = new Chart(document.getElementById('tipoNegocioChart'), {
        type: 'pie',
        data: tipoNegocioData
    });

    var sectorChart = new Chart(document.getElementById('sectorChart'), {
        type: 'pie',
        data: sectorData
    });

    var tipoSectorChart = new Chart(document.getElementById('tipoSectorChart'), {
        type: 'pie',
        data: tipoSectorData
    });

    var EstadoRelacionLaboralCarreraChart = new Chart(document.getElementById('EstadoRelacionLaboralCarreraChart'), {
        type: 'pie',
        data: EstadoRelacionLaboralCarreraData
    });

    var cargoChart = new Chart(document.getElementById('cargoChart'), {
        type: 'bar',
        data: cargoData
    });

    var RangoSalarialChart = new Chart(document.getElementById('RangoSalarialChart'), {
        type: 'bar',
        data: RangoSalarialData
    });

    var PeriodoTiempoChart = new Chart(document.getElementById('PeriodoTiempoChart'), {
        type: 'pie',
        data: PeriodoTiempoData
    });

    // mas graficos
    //import { jsPDF } from "jspdf";

    function exportChartsToPDF() {
        var doc = new jsPDF();
        var charts = document.querySelectorAll('canvas');
        function processChart(index) {
            if (index < charts.length) {
                var chart = charts[index];
                //doc.rect(10, 30, 190, 100, 'F');
                var imgData = chart.toDataURL('image/png');
                doc.addImage(imgData, 'PNG', 50, 30, 120, 120);

                if (index < charts.length - 1) {
                    doc.addPage();
                }
                processChart(index + 1);
            } else {
                doc.save('gráficos.pdf');
            }
        }
        processChart(0);
    }
    document.getElementById('exportButton').addEventListener('click', exportChartsToPDF);
    </script>
</html>
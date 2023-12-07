<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $usuariosRegistrados int */
/* @var $tituladosRegistrados int */
/* @var $tituladosPostGrado int */
/* @var $tituladosTrabajando int */
/* @var $tituladosSinTrabajo int */
/* @var $tituladosSinExperiencia int */
/* @var $tipoEmprendimiento int */
/* @var $tipoInstitucion int */

$this->title = 'Informe de Titulados';
?>

<div class="informe-pdf">
    <h1><?= Html::encode($this->title) ?></h1>

    <h2>Resumen de Estadísticas:</h2>

        <ul>
            <li>Usuarios Registrados: <?= $usuariosRegistrados ?></li>
            <li>Titulados Registrados: <?= $tituladosRegistrados ?></li>
            <li>Titulados Cursando un Post Grado: <?= $tituladosPostGrado ?></li>
            <li>Titulados Trabajando: <?= $tituladosTrabajando ?></li>
            <li>Titulados Sin Trabajo: <?= $tituladosSinTrabajo ?></li>
            <li>Titulados Sin Experiencia: <?= $tituladosSinExperiencia ?></li>
        </ul>

    <h2>Estadísticas de Retroalimentación:</h2>

    <h3>Cantidad de Titulados por Gestión</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Gestión</th>
                    <th>Cantidad de Titulados</th>
                    <th>Gestión</th>
                    <th>Cantidad de Titulados</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_chunk($gestionTitulaciones, 2, true) as $chunk): ?>
                    <tr>
                        <?php foreach ($chunk as $label => $dato): ?>
                            <td><?= Html::encode($label) ?></td>
                            <td><?= Html::encode($dato) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <h3>Tipos de Negocios</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Negocio</th>
                    <th>Cantidad de Titulados</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Emprendimiento</td>
                    <td><?= $tipoEmprendimiento ?></td>
                </tr>
                <tr>
                    <td>Institución</td>
                    <td><?= $tipoInstitucion ?></td>
                </tr>
            </tbody>
        </table>

    <h3>Sectores Productivos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Sector</th>
                    <th>Cantidad de Titulados</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sectoresDataArray = explode(',', $sectoresData);
                foreach ($sectoresLabels as $index => $sectorLabel): 
                ?>
                    <tr>
                        <td><?= Html::encode($sectorLabel) ?></td>
                        <td><?= isset($sectoresDataArray[$index]) ? Html::encode($sectoresDataArray[$index]) : '' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <h3>Sectores Económicos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Sector</th>
                    <th>Cantidad de Titulados</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $tipoSectoresDataArray = explode(',', $tipoSectoresData);
                foreach ($tipoSectoresLabels as $index => $tipoSectorLabel): 
                ?>
                    <tr>
                        <td><?= Html::encode($tipoSectorLabel) ?></td>
                        <td><?= isset($tipoSectoresDataArray[$index]) ? Html::encode($tipoSectoresDataArray[$index]) : '' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <h3>¿Tiene relación la carrera estudiada con el trabajo?</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Respuesta</th>
                    <th>Cantidad de Titulados</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Si</td>
                    <td><?= $EstadoRelacionLaboralCarreraSi ?></td>
                </tr>
                <tr>
                    <td>No</td>
                    <td><?= $EstadoRelacionLaboralCarreraNo ?></td>
                </tr>
            </tbody>
        </table>


    <h3>Cargos Ocupados</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Cargo</th>
                    <th>Cantidad de Titulados</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $cargosDataArray = explode(',', $cargosData);
                foreach ($cargosLabels as $index => $cargosLabel): 
                ?>
                    <tr>
                        <td><?= Html::encode($cargosLabel) ?></td>
                        <td><?= isset($cargosDataArray[$index]) ? Html::encode($cargosDataArray[$index]) : '' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <h3>Rangos Salariales</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Rango Salarial</th>
                    <th>Cantidad de Titulados</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rangosSalariales['labels'] as $index => $label): ?>
                    <tr>
                        <td><?= Html::encode($label) ?></td>
                        <td><?= Html::encode($rangosSalariales['datasets'][0]['data'][$index]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <h2>Periodos de Tiempo</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Periodo de Tiempo</th>
                    <th>Cantidad de Titulados</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($PeriodosDeTiempos['labels'] as $index => $label): ?>
                    <tr>
                        <td><?= Html::encode($label) ?></td>
                        <td><?= Html::encode($PeriodosDeTiempos['datasets'][0]['data'][$index]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


</div>

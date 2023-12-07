<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Acerca de:';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px; visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
                    <div class="position-relative h-100">
                        <img class="img-fluid position-absolute w-100 h-100" src="/images/about.jpg" alt="" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;">
                    <h1 class="mb-4">Bienvenido a Seguimiento a Titulados</h1>
                    <p class="mb-4">La Universidad pone a su disposición esta aplicación por la cual podrá registrar su experiencia laboral y estudios realizados de posgrado.</p>
                    <p class="mb-4">En esta aplicación usted podrá realizar las siguientes acciones:</p>
                    <div class="row gy-2 gx-4 mb-4">
                        <div class="col-sm-6">
                            <p class="mb-0">
                                <i class="fa fa-arrow-right text-primary me-2"></i>Registrar sus estudios de posgrado
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">
                                <i class="fa fa-arrow-right text-primary me-2"></i>Responder Encuestas personalizadas
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">
                                <i class="fa fa-arrow-right text-primary me-2"></i>Registrar su experiencia laboral
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">
                                <i class="fa fa-arrow-right text-primary me-2"></i>Actualizar su CV
                            </p>
                        </div>
                    </div>
                    <a class="btn btn-primary py-3 px-5 mt-2" href="<?= Yii::getAlias('@web') . '/site/login' ?>">Acceder</a>
                </div>
            </div>
</div>

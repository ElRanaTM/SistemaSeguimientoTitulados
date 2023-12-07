<?php

use yii\helpers\Html;

$this->title = 'Manual de Usuario';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-manual">
    <h1><?= Html::encode($this->title) ?></h1>

    <iframe src="<?= $pdfUrl ?>" width="100%" height="600px"></iframe>

    <div class="text-center">
        <?= Html::a('Descargar PDF', $pdfUrl, ['class' => 'btn btn-primary', 'download' => 'manual.pdf']) ?>
    </div>
</div>

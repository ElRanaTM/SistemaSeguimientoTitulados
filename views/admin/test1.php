<?php
use yii\helpers\Html;

$this->title = 'Prueba de Vista Test1';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<table class="table">
    <thead>
        <tr>
            <th>user</th>
            <th>email</th>
            <th>potencial password</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($personasTitulos as $personaTitulo): ?>
            <tr>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div><?php // \inquid\yii2-whatsapp-direct-send\AutoloadExample::widget(); ?></div>
<br>
<?php  
$pw = "123456789";
echo "p " . $pw . " p_h: " . Yii::$app->getSecurity()->generatePasswordHash($pw); 
echo "<br>";
echo  Yii::$app->urlManager->createAbsoluteUrl(['site/resetear-password', 'token' => 'asdafsfsa']);
echo "<br>";
var_dump(Yii::$app->security->generateRandomString(89) . '_' . time());  ?>
<?php
    $sql = \Yii::$app->dbAcademica->createCommand("SELECT Fotografia FROM PersonasFotografias WHERE IdPersona LIKE '002457104';")->queryColumn();
    //print_r($sql[0]);
    echo '<img src="data:image/jpeg;base64,'.base64_encode( $sql[0] ).'"/>';
    ?>

<img src="<?= Yii::$app->urlManager->createAbsoluteUrl(['/images/grafico_pie.php']) ?>" alt="Gráfico de pastel" />
<br>
<?= var_dump(Yii::getAlias('@app')); ?>

<br><br>

<?= md5('123456789') ?>
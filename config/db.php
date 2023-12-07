<?php

return [
    'class' => 'yii\db\Connection',
    'driverName' => 'sqlsrv',
    'dsn' => 'sqlsrv:Server=tcp:172.16.1.250,1433;Database=SeguimientoTitulados;Encrypt=0;TrustServerCertificate=1',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
    'on afterOpen' => function($event){$event->sender->createCommand("SET DATEFORMAT DMY; SET LANGUAGE spanish")->execute();},

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];

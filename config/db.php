<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf("mysql:host=%s;dbname=%s", DB_ADDRESS, DB_NAME),
    'username' => DB_USERNAME,
    'password' => DB_PASSWORD,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

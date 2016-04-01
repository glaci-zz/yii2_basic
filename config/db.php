<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host='.$db_host.';dbname='.$db_name,
    'username' => $db_acc,
    'password' => $db_pw,
    'charset' => 'utf8',
];

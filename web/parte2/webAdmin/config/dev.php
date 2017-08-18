<?php

// include the prod configuration
require __DIR__ . '/prod.php';

// enable the debug mode
$app['debug'] = true;

$app->register(new Silex\Provider\VarDumperServiceProvider());

//Serializer src Directory
$app['serializer.srcDir'] = __DIR__ . "/../vendor/jms/serializer/src";

//Monolog
$app['monolog.level'] = Monolog\Logger::DEBUG;
$app['monolog.logfile'] = __DIR__ . '/../logs/development.log';

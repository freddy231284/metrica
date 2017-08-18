<?php

use Silex\Provider\ServiceControllerServiceProvider;

/** @var $app \Silex\Application */

// configure your app for the production environment
$app->register(new ServiceControllerServiceProvider());

//Monolog
$app['monolog.level'] = Monolog\Logger::INFO;
$app['monolog.logPath'] = __DIR__ . '/../logs/';
$app['monolog.logfile'] = __DIR__ . '/../logs/log.log';

//Config Monolog by services and channels
$app['monolog.services'] = array(
    'security' => array(
        'type' => 'stream',
        'path' => '%kernel.logs_dir%/security.log',
        'channels' => ['security'],
    )
);

//Service API
$app['api'] = array(
    'version' => "v1",
    'endpoint' => "api"
);

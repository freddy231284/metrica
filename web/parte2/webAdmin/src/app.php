<?php

use Silex\Application;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider;
use App\Controller\ServiceXmlController;

$app = new Application();

/* ------------------------------------ Add Service Providers ------------------------------------ */


$app->register(new Provider\SessionServiceProvider());

//Add Twig service
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../resources/templates',
    'twig.form.templates' => array('bootstrap_3_layout.html.twig'),
));

//Add Serializer service provider - Symfony component
$app->register(new \Silex\Provider\SerializerServiceProvider());

//Add JmsSerializer service provider
$app->register(new \App\Provider\JmsSerializerServiceProvider(), array(
    "jms_serializer.srcDir" => __DIR__ . "/../vendor/jms/serializer/src",
));

//Add monolog
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.name' => 'broker',
    'monolog.logfile' => __DIR__ . '/../logs/log.log',
));

//Add Asset service
$base_path = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$app->register(new AssetServiceProvider(), array(
    'assets.named_packages' => array(
        'path' => array('base_path' => $base_path)
    ),
));

//Add Form Service
$app->register(new FormServiceProvider());

//Add TranslationService Provider
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));

/* ------------------------------------ Add Services ------------------------------------ */
//Monolog for each service separately including their customizations
$app['monolog.factory'] = $app->protect(function ($name) use ($app) {
    $log = new $app['monolog.logger.class']($name);

    $chanelLogfile = isset($app['monolog.channel.' . $name . '.logfile']) ? $app['monolog.channel.' . $name . '.logfile'] : $app['monolog.logfile'];
    $chanelLevel = isset($app['monolog.channel.' . $name . '.level']) ? $app['monolog.channel.' . $name . '.level'] : $app['monolog.level'];

    $handler = new \Monolog\Handler\StreamHandler($chanelLogfile, $chanelLevel);
    $log->pushHandler($handler);

    return $log;
});

//Symfony Serializer component
$app['serializer'] = function () use ($app) {
    $normalizers = [
        new ObjectNormalizer()
    ];

    $encoders = [
        new JsonEncoder(),
        new XmlEncoder(),
    ];

    return new Serializer($normalizers, $encoders);
};


//Errors
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    $app['monolog']->addError($e->getMessage());
    if ($app['debug']) {
        $app['monolog']->addError($e->getTraceAsString());
        return;
    }
    return new JsonResponse(array("statusCode" => $code, "message" => $e->getMessage(), "stacktrace" => $e->getTraceAsString()));
});

//Reports
$app['report.ui'] = function () use ($app) {
    //Call custom Monolog config using one channel for each service
    $channel = 'report.ui';
    $app['monolog.' . $channel] = function ($app) use ($channel) {
        return $app['monolog.factory']($channel);
    };
    return new \App\Controller\ReportUiController($app, $channel);
};

//Reception data API
$app['api.controller'] = function () use ($app) {
    //Call custom Monolog config using one channel for each service
    $channel = 'receptionApi';
    $app['monolog.' . $channel] = function ($app) use ($channel) {
        return $app['monolog.factory']($channel);
    };

    return new ServiceXMLController($app, $channel);
};



return $app;
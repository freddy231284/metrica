<?php
/** @var $app \Silex\Application */

use Symfony\Component\HttpFoundation\Request;


$app->get('/', function () use ($app) {
    return "Welcome to  app!.";
});

//UI admin
// define controllers for a report
$report = $app['controllers_factory'];
$report->get('/', "report.ui:index");

//Generate Report
$report->match('generate', function (Request $request) use ($app) {
    return $app['report.ui']->generate($app, $request);
}, 'GET|POST');

$report->match('details/{id}', function ($id) use ($app) {
    return $app['report.ui']->details($app, $id);
}, 'GET|POST');

$app->mount('/admin/report', $report);

// Service generate XML
$api = $app["controllers_factory"];
$api->get('/employees',"api.controller:generateXml");
//Mount controllers
$app->mount($app["api"]["endpoint"] . '/' . $app["api"]["version"], $api);



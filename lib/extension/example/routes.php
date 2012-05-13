<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//--------------------------------------------------------------
$app->match('/', function () use ($app) {
  return $app['twig']->render('index.twig');
});

$app->match('/id/{id}', function () use ($app) {

});

$app->match('/help', function () use ($app) {
  return $app['twig']->render('help.twig');
});

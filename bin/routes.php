<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//--------------------------------------------------------------
$app->match('/', function () use ($app) {
  return $app['twig']->render('index.twig');
});

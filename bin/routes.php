<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//--------------------------------------------------------------
// Custom Error Page
//--------------------------------------------------------------
$app->error(function (\Exception $e, $code) use ($app) {
  return $app['twig']->render('error.twig', array(
    'message' => $e->getMessage()
  ));
});

//--------------------------------------------------------------
// Main Routes
//--------------------------------------------------------------
$app->match('/', function () use ($app) {
  return $app['twig']->render('index.twig');
});

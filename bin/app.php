<?php

require_once __DIR__ . '/../lib/silex.phar';
$app = new Silex\Application();

require_once __DIR__ . '/config.php';

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.options'  => array(
    'cache'            => $app['debug'] ? false : $app['twig.cache_dir'],
    'strict_variables' => true,
  )
));

$app->register(new Silex\Provider\HttpCacheServiceProvider());

$app->register(new SilexExtension\PdoProvider());

$app->register(new SilexExtension\ApcProvider());

$app->register(new SilexExtension\MarkdownProvider());

$app->register(new SilexExtension\AsseticProvider(), array(
  'assetic.options' => array('debug' => $app['debug']),
  'assetic.filters' => $app->protect(function($fm) use ($app) {
    $fm->set('less', new Assetic\Filter\LessFilter($app['assetic.path_to_node']));
    $fm->set('css',  new Assetic\Filter\Yui\CssCompressorFilter($app['assetic.path_to_yui']));
    $fm->set('js',   new Assetic\Filter\Yui\JsCompressorFilter($app['assetic.path_to_yui']));
  }),
  'assetic.assets' => $app->protect(function($am, $fm) use ($app) {
    $am->set('mb', new Assetic\Asset\AssetCache(
      new Assetic\Asset\GlobAsset($app['assetic.input_mb'], array(
        $fm->get('less'), $fm->get('css')
      )), new Assetic\Cache\FilesystemCache($app['assetic.cache_path'])
    ));
    $am->set('less', new Assetic\Asset\AssetCache(
      new Assetic\Asset\GlobAsset($app['assetic.input_less'], array(
        $fm->get('less'), $fm->get('css')
      )), new Assetic\Cache\FilesystemCache($app['assetic.cache_path'])
    ));
    $am->set('js',   new Assetic\Asset\AssetCache(
      new Assetic\Asset\GlobAsset($app['assetic.input_js'], array(
        $fm->get('js')
      )), new Assetic\Cache\FilesystemCache($app['assetic.cache_path'])
    ));
    $am->get('less')->setTargetPath($app['assetic.output_css']);
    $am->get('js')->setTargetPath($app['assetic.output_js']);
    $am->get('mb')->setTargetPath($app['assetic.output_mb']);
  })
));


$app->before(function (Symfony\Component\HttpFoundation\Request $req) use ($app) {
  $app['session']->start();
  $app['locale'] = (null != $app['session']->get('locale'))
    ? $app['session']->get('locale') : 'ja-jp';
  $app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => $app['locale']
  ));
  $app['translator.loader']  = new Symfony\Component\Translation\Loader\YamlFileLoader();
  $app['twig']->addExtension(new Symfony\Bridge\Twig\Extension\TranslationExtension($app['translator']));
});


return $app;

<?php

use Avid\CandidateChallenge\Provider\CandidateChallengeControllerProvider;
use Avid\CandidateChallenge\Provider\RepositoryServiceProvider;
use Avid\CandidateChallenge\Service;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Symfony\Bundle\WebProfilerBundle\Twig\WebProfilerExtension;
use Avid\CandidateChallenge\Application;

$app['debug'] = $env === Application::ENV_DEV;

$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../resources/views',
    'twig.form.templates' => ['Form/form_div_layout.html.twig'],
]);
$app->register(new ServiceControllerServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new TranslationServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new FormServiceProvider(), [
    'form.secret' => '+D+M#?@xz@q8xlPeMuDX2,A-V|e-Z\]qwF#z@y*jE&:E7@[1WGkv051`r.RP\AK7',
]);
$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/../resources/data/app.db',
    ],
]);

if ($app['debug']) {
    $app->register(new WebProfilerServiceProvider(), [
            'profiler.cache_dir' => __DIR__ . '/../resources/cache/profiler',
    ]);
    $app->register(new Sorien\Provider\DoctrineProfilerServiceProvider());

    $app[Service::TWIG] = $app->share($app->extend(Service::TWIG, function ($twig) {
        /** @var Twig_Environment $twig */
        $twig->addExtension(new WebProfilerExtension());

        return $twig;
    }));
}

$candidateChallengeControllers = new CandidateChallengeControllerProvider();

$app->register(new RepositoryServiceProvider());
$app->register($candidateChallengeControllers);
$app->mount('/', $candidateChallengeControllers);

return $app;

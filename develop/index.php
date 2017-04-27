<?php

use Aura\Router\RouterContainer;
use ColorCLI\Logger;
use DI\ContainerBuilder;
use MicroCore\App;
use MicroCore\Logging;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonoLogger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions([
    'app.host' => '127.0.0.1',
    'app.port' => '8080',
    'Logger' => new Logging([
        new Logger(),
        new MonoLogger('monoLogger', [
            new RotatingFileHandler(__DIR__ . '/logs/app.log', 2)
        ])
    ]),
    'RouterContainer' => (function(){
        $container = new RouterContainer('/api/v1');
        $container->getMap()->get('api.test','/test', function(ServerRequestInterface $request, ResponseInterface $response){
            $response->getBody()->write('Test');
            return $response;
        });
        return $container;
    })()
]);

$app = new App($builder->build());
$app->run();
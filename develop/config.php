<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 10:42
 */

use ColorCLI\Logger;
use MicroCore\Components\Web\Service;
use MicroCore\Components\Logging;
use MicroCore\Components\Routing\Route;
use MicroCore\Enums\Verb;
use MicroCore\Interfaces\ServiceInterface;
use MicroCore\Interfaces\RouteInterface;
use MicroCore\Interfaces\RouterInterface;
use MicroCore\Components\Routing\Router;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonoLogger;
use Psr\Log\LoggerInterface;
use function DI\object;
use function DI\get;

return [
    'defaultControllerAction' => 'index',
    ServiceInterface::class => object(Service::class),
    LoggerInterface::class => object(Logging::class)->constructor(get('log.loggers')),
    'log.loggers' => [
        get(Logger::class),
        object(MonoLogger::class)->constructor('monologger', get('log.monologger.handlers'))
    ],
    'log.monologger.handlers' => [
        object(RotatingFileHandler::class)->constructor(__DIR__ . '/logs/app.log'),
    ],
    RouterInterface::class => object(Router::class),
    RouteInterface::class => object(Route::class),
    'endpoints' => [
        '/api/v{apiVersion:\d+}' => [
            '/test' => [\Application\Controllers\Test::class, 'verbs' => [Verb::GET(), Verb::POST()]],
            '/test/{id:\d+}' => [\Application\Controllers\Test::class, 'item', 'verbs' => [Verb::GET(), Verb::PUT(), Verb::DELETE()]]
        ],
    ],
];
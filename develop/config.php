<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 10:42
 */

use ColorCLI\Logger;
use MicroCore\App;
use MicroCore\Logging;
use MicroCore\RouterInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonoLogger;
use Psr\Log\LoggerInterface;
use function DI\object;
use function DI\get;

return [
    'app.host' => '127.0.0.1',
    'app.port' => '8080',
    App::class => object(),
    LoggerInterface::class => object(Logging::class)->constructor(get('log.loggers')),
    'log.loggers' => [
        get(Logger::class),
        object(MonoLogger::class)->constructor('monologger', get('log.monologger.handlers'))
    ],
    'log.monologger.handlers' => [
        object(RotatingFileHandler::class)->constructor(__DIR__ . '/logs/app.log'),
    ],
    RouterInterface::class => object(\MicroCore\Router::class),
    'endpoints' => [
        '/api/v1' => [
            '/test' => [\MicroCore\Controller::class, 'run'],
            '/test/{id}' => [\MicroCore\Controller::class, 'run']
        ]
    ]
];
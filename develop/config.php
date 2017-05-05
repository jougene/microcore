<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 10:42
 */

use ColorCLI\Logger;
use MicroCore\Enums\Verb;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonoLogger;
use function DI\object;
use function DI\get;

return [
    'container' => [
        'log.loggers' => [
            get(Logger::class),
            object(MonoLogger::class)->constructor('monologger', get('log.monologger.handlers'))
        ],
        'log.monologger.handlers' => [
            object(RotatingFileHandler::class)->constructor(__DIR__ . '/logs/app.log'),
        ],
        'endpoints' => [
            '/api/v{apiVersion:\d+}' => [
                '/test' => [\Application\Controllers\TestController::class, 'verbs' => [Verb::GET(), Verb::POST()]],
                '/test/{id:\d+}' => [\Application\Controllers\TestController::class, 'item', 'verbs' => [Verb::GET(), Verb::PUT(), Verb::DELETE()]]
            ],
            '/test' => function ($request, $response) {
                $response->getBody()->write('test');
                return $response;
            },
            '/test2' => [
                function ($request, $response) {
                    $response->getBody()->write('test');
                    return $response;
                },
                'verbs' => [Verb::GET()]
            ]
        ],
    ]
];
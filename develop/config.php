<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 10:42
 */

return [
    'app.host' => '127.0.0.1',
    'app.port' => '8080',
    'components' => [
        'Logger' => [
            'class' => MicroCore\Logging::class,
            'loggers' => [
                new Monolog\Logger('monologger', [
                    new Monolog\Handler\RotatingFileHandler(__DIR__ . '/logs/app.log', 2)
                ])
            ]
        ]
    ]
];
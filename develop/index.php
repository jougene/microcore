<?php

use DI\ContainerBuilder;
use MicroCore\Interfaces\ServiceInterface;

require_once dirname(__DIR__) . '/vendor/autoload.php';

(new ContainerBuilder())
    ->addDefinitions('config.php')
    ->build()
    ->get(ServiceInterface::class)
    ->run();
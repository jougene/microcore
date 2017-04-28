<?php

use DI\ContainerBuilder;
use MicroCore\Components\App;

require_once dirname(__DIR__) . '/vendor/autoload.php';

(new ContainerBuilder())
    ->addDefinitions('config.php')
    ->build()
    ->get(App::class)
    ->run();
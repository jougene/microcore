<?php

use DI\ContainerBuilder;
use MicroCore\Components\App;

require_once dirname(__DIR__) . '/vendor/autoload.php';

echo '<pre>';

(new ContainerBuilder())
    ->addDefinitions('config.php')
    ->build()
    ->get(App::class)
    ->run();

echo '</pre>';
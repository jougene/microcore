<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$builder = new DI\ContainerBuilder();

$app = new \MicroCore\App($builder->build());
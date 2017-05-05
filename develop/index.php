<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

(new MicroCore\Components\Web\Service(require_once 'config.php'))->run();
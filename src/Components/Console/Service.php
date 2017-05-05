<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 30.04.2017
 * Time: 17:45
 */

namespace MicroCore\Components\Console;


use ColorCLI\Logger;
use DI\ContainerBuilder;
use MicroCore\Components\AbstractService;
use MicroCore\Components\Logging;
use MicroCore\Components\Routing\Route;
use MicroCore\Components\Routing\Router;
use MicroCore\Interfaces\RouteInterface;
use MicroCore\Interfaces\RouterInterface;
use MicroCore\Interfaces\ServiceInterface;
use Psr\Log\LoggerInterface;
use function DI\object;
use function DI\get;

class Service extends AbstractService implements ServiceInterface
{

    /**
     * Run the application
     * @return void
     */
    public function run()
    {
        // TODO: Implement run() method.
    }

    public function setupContainer($config)
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            'defaultControllerAction' => 'index',
            ServiceInterface::class => $this,
            LoggerInterface::class => object(Logging::class)->constructor(get('log.loggers')),
            'log.loggers' => [
                get(Logger::class),
            ],
            RouterInterface::class => object(Router::class),
            RouteInterface::class => object(Route::class),
        ]);
        if (isset($config['container'])) {
            $builder->addDefinitions($config);
        }
        $this->container = $builder->build();
    }
}
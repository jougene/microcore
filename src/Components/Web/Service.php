<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 27.04.2017
 * Time: 14:32
 */

namespace MicroCore\Components\Web;


use ColorCLI\Logger;
use DI\ContainerBuilder;
use MicroCore\Components\AbstractService;
use MicroCore\Components\Logging;
use MicroCore\Components\Routing\Route;
use MicroCore\Components\Routing\Router;
use MicroCore\Interfaces\RouteInterface;
use MicroCore\Interfaces\RouterInterface;
use MicroCore\Interfaces\ServiceInterface;
use Microcore\Interfaces\ResponseInterface;
use Psr\Log\LoggerInterface;
use function DI\object;
use function DI\get;

class Service extends AbstractService implements ServiceInterface
{
    /**
     * @var callable
     */
    protected $requestHandler;

    public function run()
    {
        $request = new Request($this);
        $router = $this->getContainer()->get(RouterInterface::class);
        $request = $router->match($request);
        if ($request->getAttribute('_handler') === null) {
            $response = (new Response($this))->withStatus(404);
            $response->getBody()->write('Endpoint not found');
            return $response->end();
        }

        $handler = $this->requestHandler;
        /** @var ResponseInterface $response */
        $response = $handler($request, new Response($this));
        return $response->end();
    }

    public function onRequest(callable $callback)
    {
        $app = clone $this;
        $app->requestHandler = $callback;
        return $app;
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
        $builder->addDefinitions($config);
        $this->container = $builder->build();
    }
}
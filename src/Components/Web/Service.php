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
use DI\Scope;
use MicroCore\Components\AbstractService;
use MicroCore\Components\Logging;
use MicroCore\Components\Routing\Route;
use MicroCore\Components\Routing\Router;
use MicroCore\Interfaces\RequestInterface;
use MicroCore\Interfaces\RouteInterface;
use MicroCore\Interfaces\RouterInterface;
use MicroCore\Interfaces\ServiceInterface;
use Microcore\Interfaces\ResponseInterface;
use Psr\Log\LoggerInterface;
use function DI\object;
use function DI\get;

/**
 * Class Service
 * @package MicroCore\Components\Web
 */
class Service extends AbstractService implements ServiceInterface
{
    /**
     * @var callable
     */
    protected $requestHandler;

    /**
     * @var \DI\Container
     */
    protected $container;

    public function run()
    {
        $this->getLogger()->debug('Request begin');
        $request = $this->container->make(RequestInterface::class);
        $router = $this->getContainer()->get(RouterInterface::class);
        $request = $router->match($request);
        if ($request->getAttribute('_handler') === null) {
            $response = (new Response($this))->withStatus(404);
            $response->getBody()->write('Endpoint not found');
            return $response->end();
        }

        $handler = $this->requestHandler;
        /** @var ResponseInterface $response */
        $response = $handler($request, $this->container->make(ResponseInterface::class));
        $this->getLogger()->debug('Request end');
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
        $definitions = [
            LoggerInterface::class => object(Logging::class)->constructor(get('log.loggers')),
            'log.loggers' => [
                get(Logger::class),
            ],
            'endpoints' => [],
            RouterInterface::class => object(Router::class)->constructor(get('endpoints')),
            RouteInterface::class => object(Route::class)->scope(Scope::PROTOTYPE),
            RequestInterface::class => object(Request::class)->scope(Scope::PROTOTYPE),
            ResponseInterface::class => object(Response::class)->scope(Scope::PROTOTYPE),
        ];
        if (isset($config['container'])) {
            $definitions = array_merge_recursive($definitions, $config['container']);
        }
        $builder->addDefinitions($definitions);
        $this->container = $builder->build();
    }
}
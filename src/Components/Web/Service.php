<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 27.04.2017
 * Time: 14:32
 */

namespace MicroCore\Components\Web;


use MicroCore\Interfaces\RouterInterface;
use MicroCore\Interfaces\ServiceInterface;
use Psr\Container\ContainerInterface;
use Microcore\Interfaces\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Log\LoggerInterface;

class Service implements ServiceInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var callable
     */
    protected $requestHandler;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->requestHandler = function (RequestInterface $request, ResponseInterface $response) {
            return call_user_func_array($request->getAttribute('_handler'), [$request, $response]);
        };
    }

    public function run()
    {
        $request = new Request($this);
        $router = $this->getContainer()->get(RouterInterface::class);
        $request = $router->match($request);
        if ($request->getAttribute('_handler') === null) {
            $response = (new response($this))->withStatus(404);
            $response->getBody()->write('Endpoint not found');
            return $response->end();
        }

        $handler = $this->requestHandler;
        $response = $handler($request, new response($this));
        return $response->end();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function onRequest(callable $callback)
    {
        $app = clone $this;
        $app->requestHandler = $callback;
        return $app;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->container->get(LoggerInterface::class);
    }
}
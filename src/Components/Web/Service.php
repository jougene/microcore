<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 27.04.2017
 * Time: 14:32
 */

namespace MicroCore\Components\Web;


use GuzzleHttp\Psr7\Response;
use MicroCore\Interfaces\ServiceInterface;
use MicroCore\Interfaces\RouteInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
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
    }

    public function run()
    {
        $request = new Request($this);
        $route = $request->getRoute();
        if ($route !== null) {
            $response = $this->processRoute($request, $route);
        } else {
            $response = new Response(404, [], 'Endpoint not found');
        }
        $this->write($response);
    }

    public function onRequest(callable $callback)
    {
        $app = clone $this;
        $app->requestHandler = $callback;
        return $app;
    }

    public function processRoute(RequestInterface $request, RouteInterface $route)
    {
        /** @var Response $response */
        $response = call_user_func_array([$route->getHandler(), 'run'], [$request, new Response()]);
        return $response;
    }

    public function write(ResponseInterface $response)
    {
        header("HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} {$response->getReasonPhrase()}");
        foreach ($response->getHeaders() as $header) {
            header($header);
        }
        echo $response->getBody();
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->container->get(LoggerInterface::class);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
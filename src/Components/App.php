<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 27.04.2017
 * Time: 14:32
 */

namespace MicroCore\Components;


use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use MicroCore\Interfaces\RouteInterface;
use MicroCore\Interfaces\RouterInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class App
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run()
    {
        $request = new ServerRequest($_SERVER['REQUEST_METHOD'], new Uri($_SERVER['REQUEST_URI']));

        /** @var RouterInterface $router */
        $router = $this->container->get(RouterInterface::class);
        $route = $router->match($request);
        if ($route !== null) {
            $response = $this->processRoute($request, $route);
        } else {
            $response = new Response(404, [], 'Endpoint not found');
        }
        $this->write($response);
    }

    public function processRoute($request, RouteInterface $route)
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
    public function getLogger()
    {
        return $this->container->get(LoggerInterface::class);
    }

    public function getContainer()
    {
        return $this->container;
    }
}
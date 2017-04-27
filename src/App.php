<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 27.04.2017
 * Time: 14:32
 */

namespace MicroCore;


use Aura\Router\RouterContainer;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class App
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->getLogger()->info('Starting app');
    }

    public function run()
    {
        $this->getLogger()->info('App run');

        $request = new ServerRequest($_SERVER['REQUEST_METHOD'], new Uri($_SERVER['REQUEST_URI']));

        /** @var RouterContainer $router */
        $router = $this->container->get('RouterContainer');
        $route = $router->getMatcher()->match($request);
        if($router !== null) {
            $handler = $route->handler;
            /** @var Response $response */
            $response = $handler($request, new Response(200));
            echo $response->getBody();
        }
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->container->get('Logger');
    }
}
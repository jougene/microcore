<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 10:45
 */

namespace MicroCore;


use Aura\Router\RouterContainer;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    protected $app;

    /**
     * @var RouterContainer[]
     */
    protected $containers = [];

    /**
     * Router constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $endpoints = $app->getContainer()->get('endpoints');
        foreach ($endpoints as $basePath => $routes) {
            if (is_numeric($basePath)) {
                $this->addRoutes($this->basePath(), $routes);
            } else {
                $this->addRoutes($this->basePath($basePath), $routes);
            }
        }
    }

    public function addRoutes(RouterContainer $container, array $routeDefinitions = [])
    {
        foreach ($routeDefinitions as $path => $definition) {
            $container->getMap()->route($path, $path, [$definition[0], $definition[1] ?? 'index'])->allows($definition['verb'] ?? 'GET');
        }
    }

    public function basePath($path = '/')
    {
        if (!isset($this->containers[$path])) {
            $this->containers[$path] = new RouterContainer($path);
        }
        return $this->containers[$path];
    }

    /**
     * @param ServerRequestInterface $request
     * @return \Aura\Router\Route|bool
     */
    public function match(ServerRequestInterface $request)
    {
        foreach ($this->containers as $container) {
            if (($response = $container->getMatcher()->match($request)) !== false) {
                return $response;
            }
        }
        return false;
    }
}
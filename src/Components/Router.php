<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 10:45
 */

namespace MicroCore\Components;


use MicroCore\Enums\Verb;
use MicroCore\Interfaces\RouteInterface;
use MicroCore\Interfaces\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    protected $app;

    /**
     * @var Route[]
     */
    protected $routes = [];

    /**
     * Router constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $endpoints = $app->getContainer()->get('endpoints');
        foreach ($endpoints as $path => $definition) {
            $this->route($path, $definition);
        }
    }

    public function route($path, $definition, $basePath = null)
    {
        if (!is_array($definition) || isset($definition[0])) {
            // Route definition
            $verb = Verb::GET();
            if (isset($definition['verb'])) {
                $verb = new Verb($definition['verb']);
                unset($definition['verb']);
            }
            if ($basePath !== null) {
                $path = '/' . trim($basePath, '/') . '/' . trim($path, '/');
            }
            $this->routes[] = (new Route($this->app))->setPath($path)->setHandler($definition)->setVerb($verb);
        } else {
            // path prefix definition
            $basePath = $path;
            $routes = $definition;
            foreach ($routes as $path => $definition) {
                $this->route($path, $definition, $basePath);
            }
        }
    }

    public function match(ServerRequestInterface $request)
    {
        foreach ($this->routes as $route) {
            if (($route = $route->match($request)) !== false)
                return $route;
        }
        return null;
    }
}
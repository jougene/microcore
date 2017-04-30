<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 10:45
 */

namespace MicroCore\Components\Routing;


use MicroCore\Enums\Verb;
use MicroCore\Interfaces\ServiceInterface;
use MicroCore\Interfaces\RouteInterface;
use MicroCore\Interfaces\RouterInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

class Router implements RouterInterface
{
    /**
     * @var ServiceInterface
     */
    protected $app;

    /**
     * @var Route[]
     */
    protected $routes = [];

    /**
     * Router constructor.
     * @param ServiceInterface $app
     */
    public function __construct(ServiceInterface $app)
    {
        $this->app = $app;
        $endpoints = $app->getContainer()->get('endpoints');
        foreach ($endpoints as $path => $definition) {
            $this->route($path, $definition);
        }
    }

    /**
     * @param $path
     * @param $definition
     * @param null $basePath
     */
    public function route($path, $definition, $basePath = null)
    {
        if (!is_array($definition) || isset($definition[0])) {
            // Route definition
            $verbs = [Verb::GET(), Verb::POST(), Verb::PUT(), Verb::DELETE()];
            if (isset($definition['verbs'])) {
                $verbs = $definition['verbs'];
                unset($definition['verbs']);
            }
            if ($basePath !== null) {
                $path = '/' . trim($basePath, '/') . '/' . trim($path, '/');
            }
            $object = $this->app->getContainer()->get(RouteInterface::class);
            if(isset($definition['routeClass'])) {
                $object = $this->app->getContainer()->get($definition['routeClass']);
                unset($definition['routeClass']);
            }
            $route = $object->setPath($path)->setHandler($definition)->setVerbs($verbs);
            $this->routes[] = $route;
        } else {
            // path prefix definition
            $basePath = $path;
            $routes = $definition;
            foreach ($routes as $path => $definition) {
                $this->route($path, $definition, $basePath);
            }
        }
    }

    /**
     * @param RequestInterface $request
     * @return RouteInterface|null
     */
    public function match(RequestInterface $request)
    {
        foreach ($this->routes as $route) {
            if (($route = $route->match($request)) !== false)
                return $route;
        }
        return null;
    }
}
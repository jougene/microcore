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
            if (is_array($definition) && isset($definition['verbs'])) {
                $verbs = $definition['verbs'];
                unset($definition['verbs']);
            }
            if ($basePath !== null) {
                $path = '/' . trim($basePath, '/') . '/' . trim($path, '/');
            }
            $object = $this->app->getContainer()->get(RouteInterface::class);
            if (is_array($definition) && isset($definition['routeClass'])) {
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
     * @return RequestInterface|null
     */
    public function match(RequestInterface $request)
    {
        foreach ($this->routes as $route) {
            if (($route = $route->match($request)) !== false) {
                $request = $request->withAttribute('_handler', $route->getHandler());
                $request = $request->withAttribute('_params', $route->getParams());
                return $request;
            }
        }
        return null;
    }
}
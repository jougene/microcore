<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 10:45
 */

namespace MicroCore\Components\Routing;


use MicroCore\Enums\Verb;
use MicroCore\Exceptions\InvalidConfigException;
use MicroCore\Interfaces\ServiceInterface;
use MicroCore\Interfaces\RouteInterface;
use MicroCore\Interfaces\RouterInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

class Router implements RouterInterface
{

    /**
     * @var Route[]
     */
    protected $routes = [];

    /**
     * Router constructor.
     * @param array $endpoints
     * @throws InvalidConfigException if there are no endpoints specified
     */
    public function __construct($endpoints)
    {
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
            $verbs = [Verb::GET(), Verb::POST()];
            if (is_array($definition) && isset($definition['verbs'])) {
                $verbs = $definition['verbs'];
                unset($definition['verbs']);
            }
            if ($basePath !== null) {
                $path = '/' . trim($basePath, '/') . '/' . trim($path, '/');
            }
            $object = new Route();
            if (is_array($definition) && isset($definition['routeClass'])) {
                $object = $definition['routeClass'];
                $object = new $object;
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
                $request = $request->withAttribute('_handler', $route->getHandler())
                    ->withAttribute('_params', $route->getParams())
                    ->withAttribute('_verbs', $route->getVerbs());
                return $request;
            }
        }
        return $request;
    }
}
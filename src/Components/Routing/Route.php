<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 18:10
 */

namespace MicroCore\Components\Routing;


use MicroCore\Enums\Verb;
use MicroCore\Interfaces\RequestInterface;
use MicroCore\Interfaces\ResponseInterface;
use MicroCore\Interfaces\ServiceInterface;
use MicroCore\Interfaces\ControllerInterface;
use MicroCore\Interfaces\RouteInterface;
use Psr\Http\Message\ServerRequestInterface;

class Route implements RouteInterface
{
    /**
     * @var Verb[]
     */
    protected $verbs = [];

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var mixed
     */
    protected $handler = null;

    /**
     * @var ControllerInterface
     */
    protected $controller = null;

    /**
     * @var callable[]
     */
    protected $rules = [];

    /**
     * @var ServiceInterface
     */
    protected $app;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * Route constructor.
     * @param ServiceInterface $app
     */
    public function __construct(ServiceInterface $app)
    {
        $this->app = $app;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return RouteInterface
     */
    public function setPath(string $path): RouteInterface
    {
        $object = clone $this;
        $object->path = $path;
        return $object;
    }

    /**
     * @return callable
     */
    public function getHandler(): callable
    {
        if ($this->controller === null) {
            $this->controller = $this->resolveController();
        }
        return $this->controller;
    }

    /**
     * @param $handler
     * @return RouteInterface
     */
    public function setHandler($handler): RouteInterface
    {
        $object = clone $this;
        $object->handler = $handler;
        return $object;
    }

    private function resolveController()
    {
        if (is_array($this->handler)) {
            if (isset($this->handler[0])) {
                if ($this->handler[0] instanceof \Closure) {
                    return $this->handler[0];
                } elseif (is_string($this->handler[0]) && is_subclass_of($this->handler[0], ControllerInterface::class)) {
                    /** @var ControllerInterface $controllerName */
                    $controllerName = $this->handler[0];
                    $action = isset($this->handler[1]) ? $this->handler[1] : $this->app->getContainer()->get('defaultControllerAction');
                    return new $controllerName($this->app, $action);
                }
            }
        } elseif ($this->handler instanceof \Closure) {
            return $this->handler;
        } elseif (is_string($this->handler) && is_subclass_of($this->handler, ControllerInterface::class)) {
            /** @var ControllerInterface $controllerName */
            $controllerName = $this->handler;
            $action = $this->app->getContainer()->get('defaultControllerAction');
            return new $controllerName($this->app, $action);
        }
        return function (RequestInterface $request, ResponseInterface $response) {
            $response = $response->withStatus(404);
            $response->getBody()->write('Request handler not found');
        };
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool|Route
     */
    public function match(ServerRequestInterface $request)
    {
        $route = clone $this;
        foreach ($this->getRules() as $rule) {
            if (($route = $rule($request)) === false) {
                return false;
            }
        }
        return $route;
    }

    /**
     * @return callable[]
     */
    public function getRules()
    {
        if (!count($this->rules)) {
            $this->setDefaultRules();
        }
        return $this->rules;
    }

    /**
     * @param callable[] $rules
     * @param bool $setDefaults
     */
    public function setRules(array $rules, $setDefaults = true)
    {
        if ($setDefaults && !count($this->rules)) {
            $this->setDefaultRules();
        }
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    protected function setDefaultRules()
    {
        $this->rules[] = function (RequestInterface $request) {
            return Verb::OPTIONS()->equals(new Verb($request->getMethod())) || in_array($request->getMethod(), $this->verbs);
        };
        $this->rules[] = new PathMatcher($this);
    }

    /**
     * @param callable $rule
     */
    public function addRule(callable $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * @return Verb[]
     */
    public function getVerbs(): array
    {
        if (!count($this->verbs)) {
            $this->verbs = [Verb::GET(), Verb::POST(), Verb::PUT(), Verb::DELETE()];
        }
        return $this->verbs;
    }

    /**
     * @param Verb[] $verbs
     * @return RouteInterface
     */
    public function setVerbs(array $verbs): RouteInterface
    {
        $object = clone $this;
        $object->verbs = $verbs;
        return $object;
    }

    /**
     * @param string $name
     * @param $value
     * @return RouteInterface
     */
    public function withParam(string $name, $value): RouteInterface
    {
        $object = clone $this;
        $object->params[$name] = $value;
        return $object;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
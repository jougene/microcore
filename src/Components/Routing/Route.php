<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 18:10
 */

namespace MicroCore\Components\Routing;


use MicroCore\Enums\Verb;
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
     * @return ControllerInterface
     */
    public function getHandler(): ControllerInterface
    {
        if ($this->controller === null) {
            $action = $this->app->getContainer()->get('defaultControllerAction');
            if (!is_array($this->handler)) {
                $className = $this->handler;
            } else {
                $className = $this->handler[0];
                if(isset($this->handler[1]))
                    $action = $this->handler[1];
            }
            $this->controller = new $className($this->app, $action, $this->params);
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

    protected function setDefaultRules()
    {
        $this->rules[] = function (ServerRequestInterface $request) {
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
     * @param callable[] $rules
     * @param bool $setDefaults
     */
    public function setRules(array $rules, $setDefaults = true)
    {
        if($setDefaults && !count($this->rules)) {
            $this->setDefaultRules();
        }
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
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
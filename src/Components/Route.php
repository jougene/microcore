<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 18:10
 */

namespace MicroCore\Components;


use GuzzleHttp\Psr7\ServerRequest;
use MicroCore\Enums\Verb;
use MicroCore\Interfaces\ControllerInterface;
use MicroCore\Interfaces\RouteInterface;
use Psr\Http\Message\ServerRequestInterface;

class Route implements RouteInterface
{
    /**
     * @var Verb
     */
    protected $verb = null;

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
     * @var App
     */
    protected $app;

    /**
     * @var array
     */
    protected $params = [];

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->rules[] = function (ServerRequest $request) {
            if($this->getVerb()->equals(new Verb($request->getMethod()))) {
                return $this;
            }
            return false;
        };
        $this->rules[] = new PathMatcher($this);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): RouteInterface
    {
        $this->path = $path;
        return $this;
    }

    public function getHandler(): ControllerInterface
    {
        if ($this->controller === null) {
            if (!is_array($this->handler)) {
                $className = $this->handler;
                $this->controller = new $className($this->app->getContainer()->get('defaultControllerAction'), $this->params);
            } else {
                $className = $this->handler[0];
                $this->controller = new $className($this->handler[1], $this->params);
            }
        }
        return $this->controller;
    }

    public function setHandler($handler): RouteInterface
    {
        $this->handler = $handler;
        return $this;
    }

    public function match(ServerRequestInterface $request)
    {
        $route = clone $this;
        foreach ($this->rules as $rule) {
            if(($route = $rule($request)) === false) {
                return false;
            }
        }
        return $route;
    }

    public function getVerb(): Verb
    {
        if ($this->verb === null) {
            $this->verb = Verb::GET();
        }
        return $this->verb;
    }

    public function setVerb(Verb $verb): RouteInterface
    {
        $this->verb = $verb;
        return $this;
    }

    public function withParam(string $name, $value): RouteInterface
    {
        $object = clone $this;
        $object->params[$name] = $value;
        return $object;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
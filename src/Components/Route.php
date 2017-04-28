<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 18:10
 */

namespace MicroCore\Components;


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
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
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
                $this->controller = new $this->handler;
                $this->controller->setAction($this->app->getContainer()->get('defaultControllerAction'));
            } else {
                $this->controller = new $this->handler[0];
                $this->controller->setAction($this->handler[1]);
            }
        }
        return $this->controller;
    }

    public function setHandler($handler): RouteInterface
    {
        $this->handler = $handler;
        return $this;
    }

    public function match(ServerRequestInterface $request): bool
    {
        // TODO: Implement match() method.
        if (!$this->getVerb()->equals(new Verb($request->getMethod()))) {
            return false;
        }
        return true;
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
}
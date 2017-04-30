<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 18:09
 */

namespace MicroCore\Interfaces;


use MicroCore\Enums\Verb;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

interface RouteInterface
{

    /**
     * @param Verb[] $verbs
     * @return RouteInterface
     */
    public function setVerbs(array $verbs): RouteInterface;

    /**
     * @return Verb[]
     */
    public function getVerbs(): array;

    /**
     * @param string $path
     * @return RouteInterface
     */
    public function setPath(string $path): RouteInterface;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return ControllerInterface
     */
    public function getHandler(): ControllerInterface;

    /**
     * @param $handler
     * @return RouteInterface
     */
    public function setHandler($handler): RouteInterface;

    /**
     * @param string $name
     * @param $value
     * @return RouteInterface
     */
    public function withParam(string $name, $value): RouteInterface;

    /**
     * @param RequestInterface $request
     * @return bool|RouteInterface
     */
    public function match(RequestInterface $request);

    /**
     * @return array
     */
    public function getParams(): array;
}
<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 18:09
 */

namespace MicroCore\Interfaces;


use MicroCore\Enums\Verb;
use Psr\Http\Message\ServerRequestInterface;

interface RouteInterface
{

    public function setVerb(Verb $verb): RouteInterface;

    public function getVerb(): Verb;

    public function setPath(string $path): RouteInterface;

    public function getPath(): string;

    public function getHandler(): ControllerInterface;

    public function setHandler($handler): RouteInterface;

    public function match(ServerRequestInterface $request): bool;
}
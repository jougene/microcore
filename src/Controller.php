<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 14:06
 */

namespace MicroCore;


use MicroCore\Interfaces\ControllerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Controller implements ControllerInterface
{

    public function run(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($request->getUri()->getPath());
        return $response;
    }

    public function setAction(string $action)
    {
        // TODO: Implement setAction() method.
    }
}
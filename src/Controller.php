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
    protected $params = [];

    protected $action = '';

    public function __construct($action, array $params = [])
    {
        $this->action = $action;
        $this->params = $params;
    }

    public function run(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($request->getUri()->getPath());
        var_dump($this->params);
        return $response;
    }
}
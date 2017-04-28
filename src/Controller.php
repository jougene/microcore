<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 14:06
 */

namespace MicroCore;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Controller implements ControllerInterface
{

    public function run(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response = $response->withStatus(200);
        $response->getBody()->write('Test');
        return $response;
    }
}
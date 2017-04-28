<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 10:58
 */

namespace MicroCore\Interfaces;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ControllerInterface
{
    public function __construct($action, array $params = []);

    public function run(ServerRequestInterface $request, ResponseInterface $response);
}
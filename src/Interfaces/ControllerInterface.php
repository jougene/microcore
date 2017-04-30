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
    /**
     * ControllerInterface constructor.
     * @param ServiceInterface $app
     * @param string $action
     * @param array $params
     */
    public function __construct(ServiceInterface $app, $action, array $params = []);

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function run(ServerRequestInterface $request, ResponseInterface $response);

    /**
     * @return mixed
     */
    //public function options();
}
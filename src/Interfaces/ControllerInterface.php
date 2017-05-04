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
     * @param string $action
     */
    public function __construct($action);

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response);

    /**
     * @return mixed
     */
    //public function options();
}
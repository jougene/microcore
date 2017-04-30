<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 30.04.2017
 * Time: 18:07
 */

namespace MicroCore\Components;

use MicroCore\Interfaces\RequestInterface;
use MicroCore\Interfaces\ResponseInterface;
use MicroCore\Interfaces\ServiceInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractService implements ServiceInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var callable
     */
    protected $requestHandler;

    public function __construct($config = [])
    {
        $this->setupContainer($config);
        $this->requestHandler = function (RequestInterface $request, ResponseInterface $response) {
            return call_user_func_array($request->getAttribute('_handler'), [$request, $response]);
        };
    }

    protected abstract function setupContainer($config);

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->container->get(LoggerInterface::class);
    }

    public abstract function run();
}
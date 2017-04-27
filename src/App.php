<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 27.04.2017
 * Time: 14:32
 */

namespace MicroCore;


use Aura\Router\RouterContainer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class App
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->getLogger()->info('Starting app');
    }

    public function run()
    {
        $this->getLogger()->info('App run');

        /** @var RouterContainer $router */
        $router = $this->container->get('RouterContainer');
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->container->get('Logger');
    }
}
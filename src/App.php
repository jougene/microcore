<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 27.04.2017
 * Time: 14:32
 */

namespace MicroCore;


use Psr\Container\ContainerInterface;

class App
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
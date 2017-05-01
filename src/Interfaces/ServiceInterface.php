<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 29.04.2017
 * Time: 15:27
 */

namespace MicroCore\Interfaces;


use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

interface ServiceInterface
{
    /**
     * Run the application
     * @return void
     */
    public function run();

    /**
     * @return ContainerInterface DI container instance
     */
    public function getContainer();

    /**
     * @return LoggerInterface Logger instance
     */
    public function getLogger();

}
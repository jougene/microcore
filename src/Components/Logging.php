<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 27.04.2017
 * Time: 23:12
 */

namespace MicroCore\Components;


use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class Logging extends AbstractLogger
{
    /**
     * @var LoggerInterface[]
     */
    protected $loggers = [];

    /**
     * Logging constructor.
     * @param LoggerInterface[] $loggers
     */
    public function __construct(array $loggers)
    {
        $this->loggers = $loggers;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        foreach ($this->loggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }
}
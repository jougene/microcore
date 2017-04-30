<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 30.04.2017
 * Time: 13:25
 */

namespace MicroCore\Interfaces;


interface ResponseInterface extends \Psr\Http\Message\ResponseInterface
{
    public function end();
}
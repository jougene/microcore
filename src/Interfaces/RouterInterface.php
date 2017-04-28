<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 13:26
 */

namespace MicroCore\Interfaces;


use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return RouteInterface|null
     */
    public function match(ServerRequestInterface $request);
}
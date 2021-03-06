<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 13:26
 */

namespace MicroCore\Interfaces;


use Psr\Http\Message\ServerRequestInterface as RequestInterface;

interface RouterInterface
{
    /**
     * @param RequestInterface $request
     * @return RequestInterface|null
     */
    public function match(RequestInterface $request);
}
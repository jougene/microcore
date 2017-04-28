<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 22:32
 */

namespace MicroCore\Components;


use Psr\Http\Message\ServerRequestInterface;

class PathMatcher
{
    /**
     * @var Route
     */
    protected $route;

    protected $regex = '';

    protected $params = [];

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $regex = $this->buildRegex();
        if(preg_match($regex, $request->getUri()->getPath(), $matches)) {
            $route = clone $this->route;
            foreach ($this->params as $param) {
                $route = $this->route->withParam($param, $matches[$param]);
            }
            return $route;
        }
        return false;
    }

    private function buildRegex()
    {
        $this->regex = $this->route->getPath();
        $this->extractParams();
        $this->regex = '#^' . $this->regex . '$#';
        return $this->regex;
    }

    private function extractParams()
    {
        $find = '#{(\w+):?(.*?)?}#';
        preg_match_all($find, $this->regex, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            if (count($match) > 1) {
                $token = $match[0];
                $name = $match[1];
                $value = '[^\/]+';
                if (isset($match[2]) && $match[2] !== '') {
                    $value = $match[2];
                }
                $this->regex = str_replace($token, "(?P<$name>$value)", $this->regex);
                $this->params[] = $name;
            }
        }
    }
}
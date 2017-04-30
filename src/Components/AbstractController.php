<?php
/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 28.04.2017
 * Time: 14:06
 */

namespace MicroCore\Components;


use MicroCore\Interfaces\ServiceInterface;
use MicroCore\Interfaces\ControllerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

abstract class AbstractController implements ControllerInterface
{
    protected $params = [];

    private $action = '';

    protected $app;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * ControllerInterface constructor.
     * @param ServiceInterface $app
     * @param string $action
     * @param array $params
     */
    public function __construct(ServiceInterface $app, $action, array $params = [])
    {
        $this->action = $action;
        $this->params = $params;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function run(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->request = $request;
        $this->response = $response;
        $method = new \ReflectionMethod($this, $this->action);
        $ps = [];
        $params = $this->params;
        foreach ($method->getParameters() as $i => $param) {
            $name = $param->getName();
            if (isset($params[$name])) {
                if ($param->isArray()) {
                    $ps[] = is_array($params[$name]) ? $params[$name] : [$params[$name]];
                } elseif (!is_array($params[$name])) {
                    $ps[] = $params[$name];
                } else {
                    return $this->returnFalse($response);
                }
            } elseif ($param->isDefaultValueAvailable()) {
                $ps[] = $param->getDefaultValue();
            } else {
                return $this->returnFalse($response);
            }
        }
        $responseData = $method->invokeArgs($this, $ps);
        $this->response->getBody()->write($responseData);
        return $this->response;
    }

    private function returnFalse(ResponseInterface $response)
    {
        return $response->withStatus(400);
    }

    protected function setStatus($code, $reasonPhrase = '')
    {
        $this->response = $this->response->withStatus($code, $reasonPhrase);
    }
}
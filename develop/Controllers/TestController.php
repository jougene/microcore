<?php

namespace Application\Controllers;

use Application\Models\TestModel;
use Application\Schemas\TestSchema;
use MicroCore\Components\AbstractController;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;

/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 29.04.2017
 * Time: 16:55
 */
class TestController extends AbstractController
{
    public function index()
    {

    }

    public function item($apiVersion, $id)
    {
        $this->response = $this->response->withHeader('Content-type', ['application/json', 'charset=utf-8']);
        $encoder = Encoder::instance([
            TestModel::class => TestSchema::class
        ], new EncoderOptions(0, strtr('/api/v{version}', ['{version}' => $apiVersion])));
        return $encoder->encodeData(new TestModel($id));
    }
}
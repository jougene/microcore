<?php

namespace Application\Controllers;

use Application\Schemas\TestSchema;
use MicroCore\Components\AbstractController;
use Neomerx\JsonApi\Encoder\Encoder;

/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 29.04.2017
 * Time: 16:55
 */
class Test extends AbstractController
{
    public function index()
    {

    }

    public function item($apiVersion, $id)
    {
        $encoder = Encoder::instance([
            \Application\Models\Test::class => TestSchema::class
        ]);
        return $encoder->encodeData(new \Application\Models\Test($id));
    }
}
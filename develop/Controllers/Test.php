<?php

namespace Application\Controllers;

use MicroCore\Components\AbstractController;

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
        return json_encode(['version' => $apiVersion, 'id' => $id]);
    }
}
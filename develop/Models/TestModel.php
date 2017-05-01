<?php

namespace Application\Models;


class TestModel
{
    public $id;
    public $text = 'test text';

    public function __construct()
    {

    }

    public function tableName()
    {
        return 'test';
    }

}
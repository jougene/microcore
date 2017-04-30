<?php

namespace Application\Models;

class Test
{
    public $id;
    public $text = 'test text';

    public function __construct($id)
    {
        $this->id = $id;
    }
}
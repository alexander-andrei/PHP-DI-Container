<?php

namespace TestServices;


class CarEngine implements EngineInterface
{
    private $_data;

    public function __construct($data)
    {
        $this->_data = $data;
    }
}
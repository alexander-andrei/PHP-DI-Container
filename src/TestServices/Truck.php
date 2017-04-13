<?php

namespace TestServices;


class Truck
{
    private $_data;

    public function __construct($data)
    {
        $this->_data = $data;
    }

    public function runTest()
    {
        echo "TeeeesT";
    }
}
<?php

namespace TestServices;


class Car
{
    private $_speed;
    private $_engine;
    private $_sound;
    private $_logo;
    private $_name;

    public function __construct
    (
        int $speed,
        EngineInterface $engine,
        CarSoundType $sound,
        string $logo,
        string $name
    )
    {
        $this->_speed   = $speed;
        $this->_engine  = $engine;
        $this->_sound   = $sound;
        $this->_logo    = $logo;
        $this->_name    = $name;
    }

    public function runTest()
    {
        echo "TeeeesT";
    }
}
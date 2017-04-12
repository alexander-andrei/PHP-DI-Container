<?php

require_once 'autoloader.php';
require_once 'vendor/autoload.php';

define('CONTAINER_NAME', 'SuperContainer');

class Console
{
    public function main()
    {
        $c = new \Container\Compiler();
        $c->compile('SuperContainer');

        $container = new SuperContainer();
        $car = $container->get('car_service');

        $car->runTest();
    }
}

$c = new Console();
$c->main();
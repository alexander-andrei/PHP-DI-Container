<?php

require_once 'autoloader.php';
require_once 'vendor/autoload.php';

define('CONTAINER_NAME', 'SuperContainer');

class Console
{
    public function main()
    {
        $c = new \Container\Compiler\Compiler();
        $c->compile('SuperContainer', 'services.yml');

        $container = new SuperContainer();
        $car = $container->get('car_service');

        var_dump($car);
    }
}

$c = new Console();
$c->main();

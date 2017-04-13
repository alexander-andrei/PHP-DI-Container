<?php

class SuperContainer
{
    public function get($service)
    {
        $method = sprintf('get_%s', $service);

        return $this->$method();
    }

    public function get_car_service()
    {
        return new \TestServices\Car(
            123,
            $this->get_truck_service(),
            new \TestServices\Truck(),
            'string',
            'somesqlinstance'
        );
    }

    public function get_truck_service()
    {
        return new \TestServices\Truck();
    }

    public function get_super_truck_service()
    {
        return new \TestServices\Truck();
    }
}
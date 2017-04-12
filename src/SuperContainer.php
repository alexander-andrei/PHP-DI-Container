<?php 
 class SuperContainer {
public function get($service){
$method = sprintf('get_%s', $service);
return $this->$method();
}
public function get_car_service(){
return new \TestServices\Car(123);
}
public function get_truck_service(){
return new \TestServices\Truck(new \TestServices\Car());
}
public function get_super_truck_service(){
return new \TestServices\Truck($this->get_car_service());
}
}
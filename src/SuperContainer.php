<?php 
 class SuperContainer {
public function get($service){
$method = sprintf('get_%s', $service);
return $this->$method();
}
public function get_car_service(){
return new \TestServices\Car(222, $this->get_engine_service(), new \TestServices\CarSoundType(), 'CoolLogo', 'CoolCar');}
public function get_truck_service(){
return new \TestServices\Truck();}
public function get_super_truck_service(){
return new \TestServices\Truck();}
public function get_engine_service(){
return new \TestServices\CarEngine();
}
}
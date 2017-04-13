<?php 
 class Console\SuperContainer {
public function get($service){
$method = sprintf('get_%s', $service);
return $this->$method();
}
public function get_car_service(){
static $count = 0;$count++;if ($count > 3) {throw new Exception('You have a circular dependency in the service' . __METHOD__);}return new \TestServices\Car(112, $this->get_engine_service(), new \TestServices\CarSoundType(), 'CoolLogo', 'CoolCar');}
public function get_truck_service(){
static $count = 0;$count++;if ($count > 3) {throw new Exception('You have a circular dependency in the service' . __METHOD__);}return new \TestServices\Truck('somesqlinstance');}
public function get_super_truck_service(){
static $count = 0;$count++;if ($count > 3) {throw new Exception('You have a circular dependency in the service' . __METHOD__);}return new \TestServices\Truck($this->get_engine_service());}
public function get_engine_service(){
static $count = 0;$count++;if ($count > 3) {throw new Exception('You have a circular dependency in the service' . __METHOD__);}return new \TestServices\CarEngine(22);}
}
<?php

require __DIR__ . './IndexController.php';

class Factory{
	
    protected $factory = array();

    public function factoryMethod($className,$method){
        if(!isset($this->factory[$className])){
            try {
                $this->factory[$className]=new $className();
            } catch (\Exception $e) {
                throw new \Exception($className.'-- is not found',500);
            }
        }
        if(!method_exists($this->factory[$className],$method)){
            throw new \Exception($method.'-- is not found',500);
        }
        $whole['data']=$this->factory[$className]->$method();
        return $whole;
    }
}
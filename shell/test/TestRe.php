<?php

require __DIR__ . './IndexController.php';

class TestRe
{
    /*类反射*/
    public function action($_className,$_funName){
        //throw new \Exception('测试异常');
        if(!class_exists($_className)){
            throw new \Exception("'Controller in not found'",404);
        }
        $_class=new \ReflectionClass($_className);
        $_instance=$_class->newInstanceWithoutConstructor();//不通过构造函数
//        $_instance=$_class->newInstanceArgs(); //通过构造函数
        if(!$_class->hasMethod($_funName)){
            throw new \Exception("'Action is not found'",404);
        }
        $_method=$_class->getMethod($_funName);
        $whole['data']=$_method->invoke($_instance);
        return $whole;
    }
}
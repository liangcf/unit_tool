<?php

//实例化测试
include './test/Factory.php';

$runTime=$_SERVER['REQUEST_TIME'];
for($i=0;$i<1000000;$i++){
    $f=new Factory();
    $k=$f->factoryMethod('IndexController','indexAction');
    echo $i."\r\n";
}
echo 'run-time : '.(microtime(true)-$runTime);
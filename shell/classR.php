<?php
//类反射测试
include './test/TestRe.php';

$runTime=$_SERVER['REQUEST_TIME'];
for($i=0;$i<1000000;$i++){
    $f=new TestRe();
    $k=$f->action('IndexController','indexAction');
    echo $i."\r\n";
}
echo 'run-time : '.(microtime(true)-$runTime);
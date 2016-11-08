<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/10/13
 * Time: 16:36
 */



$redis=new Redis();
$redis->connect('127.0.0.1','6379');
while(1){
    $time_t=mt_rand(100,1000);
    $val=$redis->rPush('redis_test',$time_t);
    if($val){
        $log='  '.$time_t."\r\n\r\n";
        file_put_contents('redis_test.txt',$log ,FILE_APPEND);
        p($time_t);
    }else{
        exit('error!');
    }
}

closeRedis($redis);

/**
 * @var $redis Redis
 */
function closeRedis($redis){
    if($redis){
        $redis->close();
    }
}
function p($var){
    $phpRunMode=php_sapi_name();
    if(stristr('cli',$phpRunMode)){
        echo "\r\n\r\n------------------------------------------------------------\r\n";
        _var($var);
        echo "\r\n------------------------------------------------------------\r\n\r\n";
    }else{
        echo '<hr style="border-top: 1px solid #008000">';
        _var($var);
        echo '<hr style="border-top: 1px solid #008000">';
    }
}
function _var($var){
    if(is_bool($var)||is_null($var)){
        var_dump($var);
    }else{
        print_r($var);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/10/13
 * Time: 16:36
 */



$redis=new Redis();
$redis->connect('127.0.0.1','6379');
$key='page_rank';
$y=$redis->zRange($key, 0, -1,'WITHSCORES');
p($y);
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
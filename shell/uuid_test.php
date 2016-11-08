<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/10/13
 * Time: 16:36
 */


while(1){
    $vale=mt_rand(100, 500);
    $log='  '.$vale."\r\n\r\n";
    file_put_contents('mt_rand.txt',$log ,FILE_APPEND);
    p($vale);
}


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
    if(is_bool($var)){
        var_dump($var);
    }elseif(is_null($var)){
        var_dump($var);
    }else{
        print_r($var);
    }
}
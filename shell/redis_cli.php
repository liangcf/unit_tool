<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/10/13
 * Time: 16:36
 */


try{
    $redis=new Redis();
    $redis->connect('127.0.0.1','6379');
    while(1){
        $valList=$redis->lPop('redis_test');
        if($valList){
            $valStr=$redis->get('redis_str');
            $tpm=((int)($valList))+((int)($valStr));
            $redis->set('redis_str',$tpm);
            $log='  '.$valList."\r\n\r\n";
            file_put_contents('redis_str.txt',$log ,FILE_APPEND);
            p($valList);
        }else{
            p('not data!==========');
            sleep(1);
        }
    }
}catch (Exception $e){
    file_put_contents('redis_cli_error.log',date('Y-m-d H:i:s')."-".$e->getMessage()."\r\n\r\n" ,FILE_APPEND);
    closeRedis($redis);
}
closeRedis($redis);


function closeRedis(Redis $redis){
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
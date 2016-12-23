<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/9/20
 * Time: 14:38
 */

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
function pe($var){
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
    exit;
}
function pd($var){
    $phpRunMode=php_sapi_name();
    if(stristr('cli',$phpRunMode)){
        echo "\r\n\r\n------------------------------------------------------------\r\n";
        var_dump($var);
        echo "\r\n------------------------------------------------------------\r\n\r\n";
    }else{
        echo '<hr style="border-top: 1px solid #008000">';
        var_dump($var);
        echo '<hr style="border-top: 1px solid #008000">';
    }
}
function pde($var){
    $phpRunMode=php_sapi_name();
    if(stristr('cli',$phpRunMode)){
        echo "\r\n\r\n------------------------------------------------------------\r\n";
        var_dump($var);
        echo "\r\n------------------------------------------------------------\r\n\r\n";
    }else{
        echo '<hr style="border-top: 1px solid #008000">';
        var_dump($var);
        echo '<hr style="border-top: 1px solid #008000">';
    }
    exit;
}
function _var($var){
    if(is_bool($var)||is_null($var)){
        var_dump($var);
    }else{
        print_r($var);
    }
}
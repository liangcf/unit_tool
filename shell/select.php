<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/9/22
 * Time: 21:45
 */
require "../init_autoload.php";
try{
    $mysqli=new MysqliStmt();
    $ret=$mysqli->selects('users',array('sex'=>0),array('sort_order'=>'desc'),5,2);
    p($ret);
    echo "\r\n";
}catch (Exception $e){
    echo '异常';
    echo $e->getMessage();exit;
}
<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/6/14
 * Time: 20:21
 */
header("Content-Type: text/html; charset=UTF-8");
require '../../db/MysqliQuery.php';
$configTes= require '../../config/db.config.php';
$test=new MysqliQuery($configTes['default_db']);

$arr=array('id'=>'liang','name'=>'king02','sex'=>2);
//$res=$test->insert('users',$arr);
//$res=$test->updateId('users','test1',array('name'=>'liangchaofu'));
//$res=$test->update('users',array('name'=>'king0200'),array('id'=>"test1"));
//$res=$test->deleteId('users',"liang");
//$res=$test->delete('users',array('id'=>'000','sex'=>1));
//$res=$test->fetchWhere('users',array('id'=>"liang"),array('id'=>'desc','name'=>'asc'),0,3,array('id','name'));
//$res=$test->fetchAll('users',array('id'=>'desc'),0,10,array('id','name'));
$res=$test->fetchId('users','liang',array('id','name'));
//$sql="select *from users where id='liang'";
//$sql="insert into users values('ddd','ddd',0)";
//$res=$test->fetchSql($sql);
$res=$test->getCount('users',array('id'=>'ddd'));
print_r($res);

var_dump($res);

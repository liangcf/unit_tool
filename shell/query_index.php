<?php
header("Content-Type: text/html; charset=UTF-8");
require "../lib/ShellRun.php";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**********开始之处*****************************************************************************************************************************/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$mysqli=new \db\MysqliQuery();

//$arr=array('id'=>'liang','name'=>'king02','sex'=>2);
//$res=$mysqli->insert('users',$arr);
//$res=$mysqli->updateId('users','test1',array('name'=>'liangchaofu'));
//$res=$mysqli->update('users',array('name'=>'king0200'),array('id'=>"test1"));
//$res=$mysqli->deleteId('users',"liang");
//$res=$mysqli->delete('users',array('id'=>'000','sex'=>1));
//$res=$mysqli->select('users',array('id'=>"liang"),array('id'=>'desc','name'=>'asc'),0,3,array('id','name'));
//$res=$mysqli->selectAll('users',array('id'=>'desc'),0,10,array('id','name'));
//$res=$mysqli->selectId('users','liang',array('id','name'));
//$sql="select *from users where id='liang'";
//$sql="insert into users values('ddd','ddd',0)";
//$res=$mysqli->query($sql);
//$res=$mysqli->count('users',array('id'=>'ddd'));
//p($res);
//
//var_dump($res);

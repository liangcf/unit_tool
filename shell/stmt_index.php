<?php
header("Content-Type: text/html; charset=UTF-8");
require "../libs/Run.php";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**********开始之处*****************************************************************************************************************************/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$runTime=$_SERVER['REQUEST_TIME'];
try{
    $test=new \db\MysqliStmt();
//    $res=$test->insert('users',array('id'=>'000','name'=>'test','sex'=>0));
//    $res=$test->insert('users',array());
//    $res=$test->updateId('users','king',array('id'=>'ddddd','name'=>'kddding'));
//
//    $res=$test->update('users',array('name'=>'dhghsdfgdf'),array('id'=>'king'));
//
//    $res=$test->update('users',array('name'=>'liang'),array('id'=>'king'));
//    $res=$test->delete('users',array('name'=>'test','id'=>'000s1'));
//    $res=$test->select('users',array('name'=>'llllllllll'),array('id'=>'desc','name'=>'asc'),0,10,array(),array('id'=>'000'));
//    $res=$test->selectAll('users',array('id'=>'desc','name'=>'asc'),0,10,array(),array('id'=>'000','name'=>'liang'));
//    $res=$test->selectAll('users');
//    $res=$test->selectId('users','000',array('name'));
//    $res=$test->selectAll('users',array('id'=>'desc'),array('id','name'));
//    $sql="select *from users where name='liang'";
//    $sql="select *from users where id=? and name=? and sex=?";
//    $param=array('id'=>'000','name'=>'test','sex'=>'0');
//    $arr=array('name'=>'liang');
//    $res=$test->query($sql,$param);
//    $res=$test->selectId('users','10');
//    $res=$test->count('users',array('id'=>'000'));
//    $res=$test->selectNotEqualAll('users','id>=1 and id<=2');
//    var_dump($res);
//    echo '<hr>';
//    $ret=$test->selects('users',array('sex'=>'0'),array('*'));
//    p((microtime(true)-$runTime));
//    p($ret);
}catch (Exception $e){
    echo '异常:^^^^^';
    echo $e->getMessage();
}
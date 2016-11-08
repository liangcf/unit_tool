<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/6/14
 * Time: 20:21
 */
header("Content-Type: text/html; charset=UTF-8");
require '../init_autoload.php';
$reuTime=new runTime();
try{
    $test=new MysqliStmt();
//    $res=$test->insert('users',array('id'=>'000','name'=>'test','sex'=>0));
//    $res=$test->insert('users',array());
//    $res=$test->updateId('users','king',array('id'=>'ddddd','name'=>'kddding'));

//    $res=$test->ddd('testd1');

//    $res=$test->update('users',array('name'=>'dhghsdfgdf'),array('id'=>'king'));


    //$res=$test->update('users',array('name'=>'liang'),array('id'=>'king'));
//    $res=$test->delete('users',array('name'=>'test','id'=>'000s1'));
//    $res=$test->fetchWhere('users',array('name'=>'llllllllll'),array('id'=>'desc','name'=>'asc'),0,10,array(),array('id'=>'000'));
//    $res=$test->fetchAll('users',array('id'=>'desc','name'=>'asc'),0,10,array(),array('id'=>'000','name'=>'liang'));
//    $res=$test->fetchAll('users');
//    $res=$test->fetchId('users','000',array('name'));
    //$new=implode(',',array('a','b','c','d','e','f','g'));
    //echo $new;
    //$res=$test->selectAll('users',array('id'=>'desc'),array('id','name'));
    //$res=$test->selectById('users',"king",array('name'));
    //$sql="select *from users where name='liang'";
//    $sql="select *from users where id=? and name=? and sex=?";
//    $param=array('id'=>'000','name'=>'test','sex'=>'0');
    //$arr=array('name'=>'liang');
//    $res=$test->fetchSql($sql,$param);
    //$res=$test->selectById('users','10');
//    $res=$test->getCount('users',array('id'=>'000'));
//    $res=$test->notEqualAll('users','id>=1 and id<=2');
//    var_dump($res);
//    echo '<hr>';
    $ret=$test->selects('users',array('sex'=>'0'),array('*'));
    p($reuTime->finishTime());
    p($ret);
    /*$_data=microtime();
    $microsecondArr= explode(" ", microtime());
    $microsecond=$microsecondArr[0];
    p($microsecond);
    $microsecond=(float)$microsecond;
    p($microsecond);
    $tmp=$microsecond*1000*1000*100;
    p($tmp);

    list($microsecond, $timeStamp) = explode(" ", microtime());
    p($microsecond);
    $microsecond=(float)$microsecond;
    p($microsecond);
    $microsecond=$microsecond*1000*1000*100;
    p($microsecond);
    p(round($microsecond-$tmp)/100000);*/
}catch (Exception $e){
    echo '异常:^^^^^';
    echo $e->getMessage();
}
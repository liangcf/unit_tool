<?php
header("Content-Type: text/html; charset=UTF-8");

require "../init_autoload.php";
/***********************************************************************************************************************************************/
/**********开始之处*****************************************************************************************************************************/
/***********************************************************************************************************************************************/

/*$mysqli=new MysqliStmt();
$res=$mysqli->fetchAll('users');
print_r($res);*/


$url='http://hongbao.web.51nianhui.cn/hb/index/personalcenterinterindex';
$data=array('meet_id'=>931,'openid'=>'oOPChs5tikOoyZUXrtphNz1IBdvA','page_size'=>1000,'page_index'=>1);
$ret=HttpUtils::http_post($url,array('data'=>json_encode($data)));
print_r($ret);
/*$a=123;
$para=ParameterUtils::parameterValidation($a,1);
print_r($mysqliConnect);
var_dump($para);*/

<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/10/31
 * Time: 14:59
 */
header("Content-Type: text/html; charset=UTF-8");

require "../init_autoload.php";
$data = array(
    'meet_id' => 947,
    'link_id' => 7010,
    'create_time' => time(),
    'open_id' => 'onp4ruNPAWf8j5yeQ3Em7nIcZ524',
    'goods_id' => '1cc69274ca04cce95a1722b71de99479',
    'price' => 4
);

$analysis_gifts_url='http://120.25.97.131:11206/collect/present';
// 向数据收集接口发送请求
$result = HttpUtils::http_post($analysis_gifts_url, $data);

print_r($result);
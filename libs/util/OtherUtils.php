<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/11/15
 * Time: 20:22
 * 加密解密的工具
 */

namespace util;
class OtherUtils
{
    /**
     * 加密
     * 代码来源网络
     * url：--
     * @param $data
     * @param $key
     * @return string
     */
    static public function encrypt($data, $key) {
        $key = md5 ( $key );
        $x = 0;
        $len = strlen ( $data );
        $l = strlen ( $key );
        $char = '';
        for($i = 0; $i < $len; $i ++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= $key {$x};
            $x ++;
        }
        $str = '';
        for($i = 0; $i < $len; $i ++) {
            $str .= chr ( ord ( $data {$i} ) + (ord ( $char {$i} )) % 256 );
        }
        return base64_encode ( $str );
    }

    /**
     * 解密
     * @param $data
     * @param $key
     * @return string
     */
    static public function decrypt($data, $key) {
        $key = md5 ( $key );
        $x = 0;
        $data = base64_decode ( $data );
        $len = strlen ( $data );
        $l = strlen ( $key );
        $char = '';
        for($i = 0; $i < $len; $i ++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr ( $key, $x, 1 );
            $x ++;
        }
        $str = '';
        for($i = 0; $i < $len; $i ++) {
            if (ord ( substr ( $data, $i, 1 ) ) < ord ( substr ( $char, $i, 1 ) )) {
                $str .= chr ( (ord ( substr ( $data, $i, 1 ) ) + 256) - ord ( substr ( $char, $i, 1 ) ) );
            } else {
                $str .= chr ( ord ( substr ( $data, $i, 1 ) ) - ord ( substr ( $char, $i, 1 ) ) );
            }
        }
        return $str;
    }
}
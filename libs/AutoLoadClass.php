<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/9/19
 * Time: 16:49
 * url: http://git.oschina.net/liangcf/ufoahc
 * url: https://github.com/liangcf/ufoahc
 */


class AutoLoadClass{
    private static $class_Map=array();

    private static $_dirPath=__DIR__.'/';

    /**
     * 自动加载函数
     * @param $class
     * @return bool
     */
    static public function loader($class){
        if(class_exists($class,false)){
            return true;
        }
        //反斜线转正斜线
        $class=str_replace('\\','/',$class);
        $file=self::$_dirPath.'/'.$class.'.php';
        if(isset(self::$class_Map[$class])){
            return true;
        }
        if(is_file($file)){
            self::$class_Map[$class]=$class;
            include $file;
            return true;
        }
        return false;
    }
}